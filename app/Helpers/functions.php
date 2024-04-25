<?php
foreach (glob(__DIR__ . "/files/*.php") as $filename) {
    include_once $filename;
}
