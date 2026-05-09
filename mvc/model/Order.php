<?php

    class Order{
        private $id;
        private $address_id;
        private $total;
        function __construct($id,$address_id,$total){
            $this->id = $id;
            $this->address_id = $address_id;
            $this->total = $total;
        }
        function getId(){
            return $this->id;
        }
        function getAddress_id(){
            return $this->address_id;
        }
        function getTotal(){
            return $this->total;
        }
        function setId($id){
            $this->id = $id;
        }
        function setAddress_id($address_id){
            $this->address_id = $address_id;
        }
        function setTotal($total){
            $this->total = $total;
        }
    }


?>