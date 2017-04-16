<?php
/*
 * This file is part of the phpflo/phpflo package.
 *
 * (c) Henri Bergius <henri.bergius@iki.fi>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);
namespace PhpFlo\Component;

use PhpFlo\Common\ComponentInterface;
use PhpFlo\Common\ComponentTrait;

/**
 * Class ReadFile
 *
 * @package PhpFlo\Component
 * @author Henri Bergius <henri.bergius@iki.fi>
 */
class ReadFile implements ComponentInterface
{
    use ComponentTrait;

    public function __construct()
    {
        $this->inPorts()->add('source', ['datatype' => 'string']);
        $this->outPorts()
            ->add('out', ['datatype' => 'string'])
            ->add('error', []); // use defaults, datatype = all

        $this->inPorts()->source->on('data', [$this, 'readFile']);
    }

    /**
     * @param string $data
     */
    public function readFile($data)
    {
        if (!file_exists($data)) {
            $this->outPorts()->error->send("File {$data} doesn't exist");

            return;
        }

        $this->outPorts()
            ->out
            ->send(file_get_contents($data))
            ->disconnect();
    }
}
