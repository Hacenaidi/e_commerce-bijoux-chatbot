<?php
class Pannier{
    private $idpann,$id,$ref,$quant,$taille,$total_prod;
    function __construct($idpann="",$id="",$ref="",$quant="",$taille="",$total_prod="")
    {
        $this->idpann=$idpann;
        $this->id=$id;
        $this->ref=$ref;
        $this->quant=$quant;
        $this->taille=$taille;
        $this->total_prod=$total_prod;
    }
    public function getIdpann() {
        return $this->idpann;
        }
        public function getId() {
        return $this->id;
        }
        
        public function getRef() {
        return $this->ref;
        }
        
        public function getQuant() {
        return $this->quant;
        }
        
        public function getTaille() {
        return $this->taille;
        }
        
        public function getTotal_prod() {
        return $this->total_prod;
        }
        
        public function setIdpann($idpann) {
            $this->idpann = $idpann;
        }
        
        public function setId($id) {
            $this->id = $id;
        }
        
        public function setRef($ref) {
            $this->ref = $ref;
        }
        
        public function setQuant($quant) {
            $this->quant = $quant;
        }
        
        public function setTaille($taille) {
            $this->taille = $taille;
        }
        
        public function setTotal_prod($total_prod) {
            $this->total_prod = $total_prod;
        }
    
}
?>