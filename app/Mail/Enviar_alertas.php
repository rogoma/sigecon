<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class Enviar_alertas extends Mailable
{
    use Queueable, SerializesModels;

    public $contenido;

    /**
     * Crear una nueva instancia de mensaje.
     *
     * @return void
     */
    public function __construct($contenido)
    {
        $this->contenido = $contenido;
    }

    /**
     * Construir el mensaje.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.enviar_alertas')
                    ->with('contenido', $this->contenido);
    }

    // /**
    //  * Create a new message instance.
    //  *
    //  * @return void
    //  */
    // public function __construct()
    // {
    //     //
    // }

    // /**
    //  * Build the message.
    //  *
    //  * @return $this
    //  */
    // public function build()
    // {
    //     return $this->view('mails.enviar_alertas');
    // }
}
