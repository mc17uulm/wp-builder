<?php

namespace WPBuilder;

interface Program {

    public function get_identifier() : string;
    public function get_name() : string;
    public function get_description(): string;
    public function handle(int $argc, array $argv) : void;

}