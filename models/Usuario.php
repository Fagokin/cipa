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
            $_telefoneUsuario = '',  //não obrigado
            $_emailUsuario = '', //não obrigado
            $_codigoVotoUsuario = '', //não obrigado
            $_idUsuario = 0, //não obrgaigado
            $_ultimoAcessoUsuario = '', //não obrigado


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

        /**
         * Get the value of idUsuario
         */ 
        public function getIdUsuario()
        {
                return $this->idUsuario;
        }

        /**
         * Set the value of idUsuario
         *
         * @return  self
         */ 
        public function setIdUsuario($idUsuario){
                $this->idUsuario = $idUsuario;
                return $this;
        }

        /**
         * Get the value of sobrenomeUsuario
         */ 
        public function getSobrenomeUsuario()
        {
                return $this->sobrenomeUsuario;
        }

        /**
         * Set the value of sobrenomeUsuario
         *
         * @return  self
         */ 
        public function setSobrenomeUsuario($sobrenomeUsuario)
        {
                $this->sobrenomeUsuario = $sobrenomeUsuario;

                return $this;
        }

        /**
         * Get the value of nomeUsuario
         */ 
        public function getNomeUsuario()
        {
                return $this->nomeUsuario;
        }

        /**
         * Set the value of nomeUsuario
         *
         * @return  self
         */ 
        public function setNomeUsuario($nomeUsuario)
        {
                $this->nomeUsuario = $nomeUsuario;
                return $this;
        }

        /**
         * Get the value of emailUsuario
         */ 
        public function getEmailUsuario()
        {
                return $this->emailUsuario;
        }

        /**
         * Set the value of emailUsuario
         *
         * @return  self
         */ 
        public function setEmailUsuario($emailUsuario)
        {
                $this->emailUsuario = $emailUsuario;

                return $this;
        }

        /**
         * Get the value of senhaUsuario
         */ 
        public function getSenhaUsuario()
        {
                return $this->senhaUsuario;
        }

        /**
         * Set the value of senhaUsuario
         *
         * @return  self
         */ 
        public function setSenhaUsuario($senhaUsuario)
        {
                $this->senhaUsuario = $senhaUsuario;

                return $this;
        }

        /**
         * Get the value of datadeNascimentoUuario
         */ 
        public function getDatadeNascimentoUuario()
        {
                return $this->datadeNascimentoUuario;
        }

        /**
         * Set the value of datadeNascimentoUuario
         *
         * @return  self
         */ 
        public function setDatadeNascimentoUuario($datadeNascimentoUuario)
        {
                $this->datadeNascimentoUuario = $datadeNascimentoUuario;

                return $this;
        }

        /**
         * Get the value of dataContratacaoUsuario
         */ 
        public function getDataContratacaoUsuario()
        {
                return $this->dataContratacaoUsuario;
        }

        /**
         * Set the value of dataContratacaoUsuario
         *
         * @return  self
         */ 
        public function setDataContratacaoUsuario($dataContratacaoUsuario)
        {
                $this->dataContratacaoUsuario = $dataContratacaoUsuario;

                return $this;
        }

        /**
         * Get the value of ativoUsuario
         */ 
        public function getAtivoUsuario()
        {
                return $this->ativoUsuario;
        }

        /**
         * Set the value of ativoUsuario
         *
         * @return  self
         */ 
        public function setAtivoUsuario($ativoUsuario)
        {
                $this->ativoUsuario = $ativoUsuario;

                return $this;
        }

        /**
         * Get the value of admUsuario
         */ 
        public function getAdmUsuario()
        {
                return $this->admUsuario;
        }

        /**
         * Set the value of admUsuario
         *
         * @return  self
         */ 
        public function setAdmUsuario($admUsuario)
        {
                $this->admUsuario = $admUsuario;

                return $this;
        }

        /**
         * Get the value of matriculaUsuario
         */ 
        public function getMatriculaUsuario()
        {
                return $this->matriculaUsuario;
        }

        /**
         * Set the value of matriculaUsuario
         *
         * @return  self
         */ 
        public function setMatriculaUsuario($matriculaUsuario)
        {
                $this->matriculaUsuario = $matriculaUsuario;

                return $this;
        }

        /**
         * Get the value of cpfUsuario
         */ 
        public function getCpfUsuario()
        {
                return $this->cpfUsuario;
        }

        /**
         * Set the value of cpfUsuario
         *
         * @return  self
         */ 
        public function setCpfUsuario($cpfUsuario)
        {
                $this->cpfUsuario = $cpfUsuario;

                return $this;
        }

        /**
         * Get the value of telefoneUsuario
         */ 
        public function getTelefoneUsuario()
        {
                return $this->telefoneUsuario;
        }

        /**
         * Set the value of telefoneUsuario
         *
         * @return  self
         */ 
        public function setTelefoneUsuario($telefoneUsuario)
        {
                $this->telefoneUsuario = $telefoneUsuario;

                return $this;
        }

        /**
         * Get the value of codigoVotoUsuario
         */ 
        public function getCodigoVotoUsuario()
        {
                return $this->codigoVotoUsuario;
        }

        /**
         * Set the value of codigoVotoUsuario
         *
         * @return  self
         */ 
        public function setCodigoVotoUsuario($codigoVotoUsuario)
        {
                $this->codigoVotoUsuario = $codigoVotoUsuario;

                return $this;
        }

        /**
         * Get the value of ultimoAcessoUsuario
         */ 
        public function getUltimoAcessoUsuario()
        {
                return $this->ultimoAcessoUsuario;
        }

        /**
         * Set the value of ultimoAcessoUsuario
         *
         * @return  self
         */ 
        public function setUltimoAcessoUsuario($ultimoAcessoUsuario)
        {
                $this->ultimoAcessoUsuario = $ultimoAcessoUsuario;

                return $this;
        }
    }

?>