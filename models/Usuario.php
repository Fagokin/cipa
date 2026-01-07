<?php
    class Usuario {
        private int $idUsuario;
        private string $nomeUsuario;
        private string $sobrenomeUsuario;
        private string $emailUsuario;
        private string $senhaUsuario;
        private string $datadeNascimentoUuario;
        private string $dataContratacaoUsuario;

        private bool $ativoUsuario;
        private bool $admUsuario;
        private string $matriculaUsuario;
        private string $cpfUsuario;
        private string $telefoneUsuario;
        private string $codigoVotoUsuario;
        private string $ultimoAcessoUsuario;

        public function __construct(
            $_nomeUsuario,
            $_sobrenomeUsuario,
            $_senhaUsuario,
            $_dataNascimentoUsuario,
            $_dataContratacaoUsuario,
            $_ativoUsuario,
            $_admUsuario,
            $_matriculaUsuario,
            $_cpfUsuario,
            $_telefoneUsuario = '',  
            $_emailUsuario = '', 
            $_codigoVotoUsuario = '', 
            $_idUsuario = 0, 
            $_ultimoAcessoUsuario = '', 


        ) {
            $this->nomeUsuario = $_nomeUsuario;
            $this->sobrenomeUsuario = $_sobrenomeUsuario;
            $this->emailUsuario = $_emailUsuario;
            $this->senhaUsuario = $_senhaUsuario;
            $this->datadeNascimentoUuario = $_dataNascimentoUsuario;
            $this->dataContratacaoUsuario = $_dataContratacaoUsuario;
            $this->ativoUsuario = $_ativoUsuario;
            $this->admUsuario = $_admUsuario;
            $this->matriculaUsuario = $_matriculaUsuario;
            $this->cpfUsuario = $_cpfUsuario;
            $this->telefoneUsuario = $_telefoneUsuario;
            $this->codigoVotoUsuario = $_codigoVotoUsuario;
            $this->idUsuario = $_idUsuario;
            $this->ultimoAcessoUsuario = $_ultimoAcessoUsuario;
        }

        public function getIdUsuario()
        {
                return $this->idUsuario;
        }

        public function setIdUsuario($idUsuario){
                $this->idUsuario = $idUsuario;
                return $this;
        }

        public function getSobrenomeUsuario()
        {
                return $this->sobrenomeUsuario;
        }

        public function setSobrenomeUsuario($sobrenomeUsuario)
        {
                $this->sobrenomeUsuario = $sobrenomeUsuario;

                return $this;
        }

        public function getNomeUsuario()
        {
                return $this->nomeUsuario;
        }

        public function setNomeUsuario($nomeUsuario)
        {
                $this->nomeUsuario = $nomeUsuario;
                return $this;
        }

        public function getEmailUsuario()
        {
                return $this->emailUsuario;
        }

        public function setEmailUsuario($emailUsuario)
        {
                $this->emailUsuario = $emailUsuario;

                return $this;
        }

        public function getSenhaUsuario()
        {
                return $this->senhaUsuario;
        }

        public function setSenhaUsuario($senhaUsuario)
        {
                $this->senhaUsuario = $senhaUsuario;

                return $this;
        }

        public function getDatadeNascimentoUuario()
        {
                return $this->datadeNascimentoUuario;
        }
-
        public function setDatadeNascimentoUuario($datadeNascimentoUuario)
        {
                $this->datadeNascimentoUuario = $datadeNascimentoUuario;

                return $this;
        }

        public function getDataContratacaoUsuario()
        {
                return $this->dataContratacaoUsuario;
        }

        public function setDataContratacaoUsuario($dataContratacaoUsuario)
        {
                $this->dataContratacaoUsuario = $dataContratacaoUsuario;

                return $this;
        }

        public function getAtivoUsuario()
        {
                return $this->ativoUsuario;
        }

        public function setAtivoUsuario($ativoUsuario)
        {
                $this->ativoUsuario = $ativoUsuario;

                return $this;
        }

        public function getAdmUsuario()
        {
                return $this->admUsuario;
        }

        public function setAdmUsuario($admUsuario)
        {
                $this->admUsuario = $admUsuario;

                return $this;
        }

        public function getMatriculaUsuario()
        {
                return $this->matriculaUsuario;
        }

        public function setMatriculaUsuario($matriculaUsuario)
        {
                $this->matriculaUsuario = $matriculaUsuario;

                return $this;
        }

        public function getCpfUsuario()
        {
                return $this->cpfUsuario;
        }

        public function setCpfUsuario($cpfUsuario)
        {
                $this->cpfUsuario = $cpfUsuario;

                return $this;
        }

        public function getTelefoneUsuario()
        {
                return $this->telefoneUsuario;
        }

        public function setTelefoneUsuario($telefoneUsuario)
        {
                $this->telefoneUsuario = $telefoneUsuario;

                return $this;
        }

        public function getCodigoVotoUsuario()
        {
                return $this->codigoVotoUsuario;
        }

        public function setCodigoVotoUsuario($codigoVotoUsuario)
        {
                $this->codigoVotoUsuario = $codigoVotoUsuario;

                return $this;
        }

        public function getUltimoAcessoUsuario()
        {
                return $this->ultimoAcessoUsuario;
        }

        public function setUltimoAcessoUsuario($ultimoAcessoUsuario)
        {
                $this->ultimoAcessoUsuario = $ultimoAcessoUsuario;

                return $this;
        }
    }


?>
