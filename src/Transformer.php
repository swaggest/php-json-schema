<?php

namespace Yaoi\Schema;


interface Transformer
{
    public function import($data);
}