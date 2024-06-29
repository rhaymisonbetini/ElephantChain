<?php

namespace Rhaymison\ElephantChain\Chains;

use Exception;

class SequentialChain
{
    /**
     * @throws Exception
     */
    public function dispatchSequence(array $chains)
    {
        $input = null;

        foreach ($chains as $chain) {
            if (is_callable($chain)) {
                $input = $chain($input);
            } else {
                throw new Exception("Each chain must be a callable.");
            }
        }
        return $input;
    }

}