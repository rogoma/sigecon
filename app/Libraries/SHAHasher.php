<?php

namespace App\Libraries;
use Illuminate\Contracts\Hashing\Hasher;

/**
 * Hasher retrocompatible: los hashes nuevos se generan con bcrypt (fuerte),
 * pero check() todavía reconoce los hashes SHA1 antiguos para no invalidar
 * las contraseñas ya existentes en la base de datos. Cuando un usuario con
 * hash SHA1 hace login exitoso, needsRehash() avisa para que se regenere
 * su hash a bcrypt (ver HomeController::checkLogin).
 */
class SHAHasher implements Hasher {

    /**
     * Get information about the given hashed value.
     *
     * @param  string  $hashedValue
     * @return array
     */
    public function info($hashedValue){
        return password_get_info($hashedValue);
    }

    /**
    * Hash the given value.
    *
    * @param  string  $value
    * @return array   $options
    * @return string
    */
    public function make($value, array $options = array()) {
        return password_hash($value, PASSWORD_BCRYPT);
    }

    /**
    * Check the given plain value against a hash.
    *
    * @param  string  $value
    * @param  string  $hashedValue
    * @param  array   $options
    * @return bool
    */
    public function check($value, $hashedValue, array $options = array()) {
        if ($this->isLegacySha1($hashedValue)) {
            return hash_equals(hash('sha1', $value), $hashedValue);
        }

        return password_verify($value, $hashedValue);
    }

    /**
    * Check if the given hash has been hashed using the given options.
    *
    * @param  string  $hashedValue
    * @param  array   $options
    * @return bool
    */
    public function needsRehash($hashedValue, array $options = array()) {
        if ($this->isLegacySha1($hashedValue)) {
            return true;
        }

        return password_needs_rehash($hashedValue, PASSWORD_BCRYPT);
    }

    /**
     * Un hash SHA1 legado es un string hexadecimal de 40 caracteres;
     * un hash bcrypt real nunca tiene ese formato (empieza con "$2y$", etc.).
     *
     * @param  string  $hashedValue
     * @return bool
     */
    private function isLegacySha1($hashedValue) {
        return (bool) preg_match('/^[a-f0-9]{40}$/i', $hashedValue);
    }

}
