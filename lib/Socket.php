<?php

namespace Sabre\Net;

use Sabre\Net\Socket;
use Sabre\Event;

/**
 * The Socket class represents a single client socket.
 *
 * @copyright Copyright (C) 2014 fruux GmbH (https://fruux.com/).
 * @author Dominik Tobschall (http://tobschall.de/)
 * @license http://sabre.io/license/ Modified BSD License
 */
class Socket implements Event\EventEmitterInterface {

    use Event\EventEmitterTrait;

    /**
     * Resource id.
     *
     * @var int
     */
    protected $id;

    /**
     * Resource name.
     *
     * @var string
     */
    protected $name;

    /**
     * The actual stream
     *
     * @var resource
     */
    protected $stream;

    /**
     * Creates a new Socket object.
     *
     * @param resource $stream
     */
    public function __construct($stream) {

        $this->id = (int)$stream;
        $this->name = stream_socket_get_name($stream, true);
        $this->stream = $stream;

    }

    /**
     * Returns the resource id.
     *
     * @return int
     */
    public function getId() {

        return $this->id;

    }

    /**
     * Returns the resource name.
     *
     * @return string
     */
    public function getName() {

        return $this->name;

    }

    /**
     * Returns the stream.
     *
     * @return resource
     */
    public function getStream() {

        return $this->stream;

    }

    /**
     * Reads data from the stream.
     *
     * @return string
     */
    public function read() {

        $data = fgets($this->stream);

        if($data) {
            $this->emit('data', [$this, $data]);
        }

        return $data;

    }

    /**
     * Sends data to the stream.
     *
     * @param string $data
     * @return void
     */
    public function send($data) {

        fwrite($this->stream, $data);

    }

    /**
     * Disconnect the client.
     *
     * @return void
     */
    public function disconnect() {

        $this->emit('disconnect', [$this]);
        fclose($this->stream);

    }

}
