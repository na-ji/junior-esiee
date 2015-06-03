<?php

namespace JuniorEsiee\CommentBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class JuniorEsieeCommentBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'FOSCommentBundle';
    }
}
