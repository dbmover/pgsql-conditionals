<?php

/**
 * @package Dbmover
 * @subpackage Pgsql
 * @subpackage Conditionals
 *
 * Gather all conditionals and optionally wrap them in a "lambda".
 */

namespace Dbmover\Pgsql\Conditionals;

use Dbmover\Conditionals;

class Plugin extends Conditionals\Plugin
{
    protected function wrap(string $sql) : string
    {
        $tmp = 'tmp_'.md5(microtime(true));
        return <<<EOT
DROP FUNCTION IF EXISTS $tmp();
CREATE FUNCTION $tmp() RETURNS void AS $$
BEGIN
    $sql
END;
$$ LANGUAGE 'plpgsql';
SELECT $tmp();
DROP FUNCTION $tmp();

EOT;
    }
}

