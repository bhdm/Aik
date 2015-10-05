<?php
    namespace Route;

    class Route{

        protected $url;

        protected $data;

        public function __construct($url = null){
            $this->url = $url;
        }

        public function getDomain(){
            $this->data = parse_url($this->url);
        }
    }
