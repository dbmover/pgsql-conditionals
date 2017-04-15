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
        $database = $this->loader->getDatabase();
        $tmp = 'tmp_'.md5(microtime(true));
        return <<<EOT
DROP FUNCTION IF EXISTS $tmp();
CREATE FUNCTION $tmp() RETURNS void AS $$
DECLARE DBMOVER_DATABASE TEXT;
BEGIN
    DBMOVER_DATABASE := '$database';
    $sql
END;
$$ LANGUAGE 'plpgsql';
SELECT $tmp();
DROP FUNCTION $tmp();

EOT;
    }
}

