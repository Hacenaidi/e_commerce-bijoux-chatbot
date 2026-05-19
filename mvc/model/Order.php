<?php

    class Order{
        private $id;
        private $address_id;
        private $total;
        private $status;
        function __construct($id,$address_id,$total,$status = 'pending'){
            $this->id = $id;
            $this->address_id = $address_id;
            $this->total = $total;
            $this->status = $status;
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
        function getStatus(){
            return $this->status;
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
        function setStatus($status){
            $this->status = $status;
        }
    }


?>