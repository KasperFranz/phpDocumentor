<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\Renderers\LaTeX;

use phpDocumentor\Guides\Nodes\CodeNode;
use phpDocumentor\Guides\Renderers\NodeRenderer;
use phpDocumentor\Guides\TemplateRenderer;

class CodeNodeRenderer implements NodeRenderer
{
    /** @var CodeNode */
    private $codeNode;

    /** @var TemplateRenderer */
    private $templateRenderer;

    public function __construct(CodeNode $codeNode, TemplateRenderer $templateRenderer)
    {
        $this->codeNode         = $codeNode;
        $this->templateRenderer = $templateRenderer;
    }

    public function render() : string
    {
        return $this->templateRenderer->render('code.tex.twig', [
            'codeNode' => $this->codeNode,
        ]);
    }
}
