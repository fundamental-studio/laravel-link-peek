<?php

    namespace Fundamental\Peekr;

    use Exception;

    class Peekr
    {
        public $url;
        public $sanitizedUrl;
        public $parsedUrl;

        public $image;
        public $title;
        public $description;

        public $isSecure;
        public $meta;
        public $content;
        
        public function __construct(String $url, Bool $isSecure = true)
        {
            $this->url = $url;
            $this->isSecure = $isSecure;

            $this->sanitizeUrl($url);
            $this->parseUrl($this->sanitizedUrl);

            return $this;
        }

        public function sanitizeUrl(String $url)
        {
            $this->sanitizedUrl = htmlspecialchars(trim($url), ENT_QUOTES, 'ISO-8859-1', TRUE);
        }

        public function parseUrl(String $url)
        {
            $this->parsedUrl = parse_url($url);

            if ($this->parsedUrl === false or $this->parsedUrl == null) {
                throw new Exception('Malformed or invalid url.');
            }

            if ($this->isSecure and $this->getScheme() == 'http') {
                throw new Exception('Insecure url provided when strict flag is turned on.');
            }
        }

        public function getParsedUrl()
        {
            return $this->parsedUrl;
        }

        public function getScheme()
        {
            return $this->parsedUrl['scheme'];
        }

        public function getHost()
        {
            return $this->parsedUrl['host'];
        }

        public function getQueryString()
        {
            return $this->parsedUrl['query'];
        }

        public function getQueryArray(): array
        {
            parse_str($this->getQueryString(), $data);

            return $data;
        }

        public function getPath()
        {
            return $this->parsedUrl['path'];
        }

        public function getFragment()
        {
            return $this->parsedUrl['fragment'];
        }

        public function getUser()
        {
            return $this->parsedUrl['user'];
        }

        public function getPassword()
        {
            return $this->parsedUrl['pass'];
        }

        public function getPort()
        {
            return $this->parsedUrl['port'];
        }

        public function getTitle()
        {
            return $this->title;
        }

        public function fetchUrl()
        {
            $file = fopen($this->sanitizedUrl, 'r');

            if (!$file) {
                throw new Exception('Could not read the provided url.');
            }

            $content = '';
            while (!feof($file)) {
                $this->content .= fgets($file, 1024);
            }

            fclose($file);
        }

        public function getMetaTags()
        {
            $this->meta = get_meta_tags($this->sanitizedUrl);
        }

        protected function findTitle()
        {
            if (array_key_exists('og:title', $this->meta))
            {
                $this->title = $this->meta['og:title'];
            }
            else if (array_key_exists('twitter:title', $this->meta))
            {
                $this->title = $this->meta['twitter:title'];
            }
            else
            {
                preg_match_all('/<title>(.+)<\/title>/i', $this->content, $title, PREG_PATTERN_ORDER);

                if (!is_array($title[1]))
                    $this->title = $title[1];
                else
                {
                    if (count($title[1]) > 0) {
                        $this->title = $title[1][0];
                    }
                    else $this->title = '';
                }
            }
        }

        protected function findDescription()
        {
            if (array_key_exists('og:description', $this->meta))
            {
                $this->description = $this->meta['description'];
            }
            else if (array_key_exists('og:description', $this->meta))
            {
                $this->description = $this->meta['og:description'];
            }
            else if (array_key_exists('twitter:description', $this->meta))
            {
                $this->description = $this->meta['twitter:description'];
            }
            else
            {
                $this->description = '';
            }
        }

        protected function findImage()
        {
            if (array_key_exists('og:image', $this->meta))
            {
                $this->image = $this->meta['og:image'];
            }
            else if (array_key_exists('og:image:src', $this->meta))
            {
                $this->image = $this->meta['og:image:src'];
            }
            else if (array_key_exists('twitter:image', $this->meta))
            {
                $this->image = $this->meta['twitter:image'];
            }
            else if (array_key_exists('twitter:image:src', $this->meta))
            {
                $this->image = $this->meta['twitter:image:src'];
            }
            else
            {
                $images = [];
                preg_match_all('/<img[^>]*'.'src=[\"|\'](.*)[\"|\']/Ui', $this->content, $images, PREG_PATTERN_ORDER);

                foreach ($images as $image)
                {
                    if (getimagesize($image))
                    {
                        list($width) = getimagesize($image);

                        if ($width > 700)
                        {
                            $this->image = $image;
                            break;
                        }
                    }
                }
            }
        }

        public function generatePreview()
        {
            return `
                <div>
                    <img src="{$this->image}" alt="">
                    <strong>{$this->title}</strong>
                    <p>{$this->description}</p>
                    <small>{$this->sanitizedUrl}</small>
                </div>
            `;
        }

        public function peek()
        {
            $this->fetchUrl();
            $this->getMetaTags();

            $this->findTitle();
            $this->findDescription();
            $this->findImage();

            return $this->generatePreview();
        }
    }