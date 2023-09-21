<?php

namespace Arandu\LaravelMuiAdmin\Services;

class JsService
{
    private $data = [];

    private $catchables = [];

    /**
     * Adiciona uma chave e valor para serem capturadas
     * pelo React a partir da função global `blade`.
     *
     * ```php
     * // Adicionar uma variavel no backend
     * $react->set('nomeVariavel', 'valor');
     * $react->set('usuario', auth()->user());
     * ```
     *
     * ```js
     * // Obter uma variavel no frontend
     * console.log(blade('nomeVariavel')); // 'valor'
     * console.log(blade('usuario')); // { id: 1, name: 'John Doe', ... }
     * ```
     *
     * @param mixed $key   - A chave que será usada para armazenar o valor
     * @param mixed $value - O valor que será armazenado
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function get($key)
    {
        if (!isset($this->data[$key])) {
            return null;
        }

        return $this->data[$key];
    }

    /**
     * Adiciona uma ou mais chaves de erros para serem
     * capturadas pelo React.
     *
     * @param array|string $key
     */
    public function catches($key)
    {
        $keys = is_array($key) ? $key : [$key];

        foreach ($keys as $key) {
            if (!in_array($key, $this->catchables)) {
                $this->catchables[] = $key;
            }
        }
    }

    public function all()
    {
        return collect($this->data)
            ->map(fn ($value) => is_object($value) && method_exists($value, 'toArray')
                ? $value->toArray()
                : $value);
    }

    public function catchables()
    {
        return $this->catchables;
    }
}
