<?php

declare(strict_types=1);

/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link https://phpdoc.org
 * @author Ryan Weaver <ryan@symfonycasts.com> on the original DocBuilder.
 * @author Mike van Riel <me@mikevanriel.com> for adapting this to phpDocumentor.
 */

namespace phpDocumentor\Guides\RestructuredText\HTML;

use phpDocumentor\Guides\Nodes\AnchorNode;
use phpDocumentor\Guides\Nodes\CallableNode;
use phpDocumentor\Guides\Nodes\CodeNode;
use phpDocumentor\Guides\Nodes\DefinitionListNode;
use phpDocumentor\Guides\Nodes\DocumentNode;
use phpDocumentor\Guides\Nodes\FigureNode;
use phpDocumentor\Guides\Nodes\ImageNode;
use phpDocumentor\Guides\Nodes\ListNode;
use phpDocumentor\Guides\Nodes\MetaNode;
use phpDocumentor\Guides\Nodes\ParagraphNode;
use phpDocumentor\Guides\Nodes\QuoteNode;
use phpDocumentor\Guides\Nodes\SectionBeginNode;
use phpDocumentor\Guides\Nodes\SectionEndNode;
use phpDocumentor\Guides\Nodes\SeparatorNode;
use phpDocumentor\Guides\Nodes\SpanNode;
use phpDocumentor\Guides\Nodes\TableNode;
use phpDocumentor\Guides\Nodes\TitleNode;
use phpDocumentor\Guides\Nodes\TocNode;
use phpDocumentor\Guides\Renderers\CallableNodeRenderer;
use phpDocumentor\Guides\Renderers\CallableNodeRendererFactory;
use phpDocumentor\Guides\Renderers\Html\AnchorNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\CodeNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\DefinitionListNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\DocumentNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\FigureNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\ImageNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\ListRenderer;
use phpDocumentor\Guides\Renderers\Html\MetaNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\ParagraphNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\QuoteNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\SectionBeginNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\SectionEndNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\SeparatorNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\SpanNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\TableNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\TitleNodeRenderer;
use phpDocumentor\Guides\Renderers\Html\TocNodeRenderer;
use phpDocumentor\Guides\Renderers\ListNodeRenderer;
use phpDocumentor\Guides\Renderers\NodeRendererFactory;
use phpDocumentor\Guides\RestructuredText;
use phpDocumentor\Guides\RestructuredText\Formats\Format;
use phpDocumentor\Guides\TemplateRenderer;

final class HTMLFormat implements Format
{
    private $templateRenderer;
    private $globalTemplatesPath;
    private $subFolder;

    public function __construct(
        TemplateRenderer $templateRenderer,
        string $globalTemplatesPath,
        string $subFolder
    ) {
        $this->templateRenderer = $templateRenderer;
        $this->globalTemplatesPath = $globalTemplatesPath;
        $this->subFolder = $subFolder;
    }

    public function getFileExtension() : string
    {
        return Format::HTML;
    }

    public function getDirectives() : array
    {
        return [
            new RestructuredText\HTML\Directives\Image(),
            new RestructuredText\HTML\Directives\Figure(),
            new RestructuredText\HTML\Directives\Meta(),
            new RestructuredText\HTML\Directives\Stylesheet(),
            new RestructuredText\HTML\Directives\Title(),
            new RestructuredText\HTML\Directives\Url(),
            new RestructuredText\HTML\Directives\Div(),
            new RestructuredText\HTML\Directives\Wrap('note'),
            new RestructuredText\HTML\Directives\ClassDirective(),
        ];
    }

    /**
     * @return NodeRendererFactory[]
     */
    public function getNodeRendererFactories() : array
    {
        $nodeRendererFactories = [
            AnchorNode::class => new CallableNodeRendererFactory(
                function (AnchorNode $node) {
                    return new AnchorNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            DefinitionListNode::class => new CallableNodeRendererFactory(
                function (DefinitionListNode $node) {
                    return new DefinitionListNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            FigureNode::class => new CallableNodeRendererFactory(
                function (FigureNode $node) {
                    return new FigureNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            ImageNode::class => new CallableNodeRendererFactory(
                function (ImageNode $node) {
                    return new ImageNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            ListNode::class => new CallableNodeRendererFactory(
                function (ListNode $node) {
                    return new ListNodeRenderer(
                        $node,
                        new ListRenderer($node, $this->templateRenderer)
                    );
                }
            ),
            MetaNode::class => new CallableNodeRendererFactory(
                function (MetaNode $node) {
                    return new MetaNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            ParagraphNode::class => new CallableNodeRendererFactory(
                function (ParagraphNode $node) {
                    return new ParagraphNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            QuoteNode::class => new CallableNodeRendererFactory(
                function (QuoteNode $node) {
                    return new QuoteNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            SeparatorNode::class => new CallableNodeRendererFactory(
                function (SeparatorNode $node) {
                    return new SeparatorNodeRenderer(
                        $this->templateRenderer
                    );
                }
            ),
            TableNode::class => new CallableNodeRendererFactory(
                function (TableNode $node) {
                    return new TableNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            TitleNode::class => new CallableNodeRendererFactory(
                function (TitleNode $node) {
                    return new TitleNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            TocNode::class => new CallableNodeRendererFactory(
                function (TocNode $node) {
                    return new TocNodeRenderer(
                        $node->getEnvironment(),
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            CallableNode::class => new CallableNodeRendererFactory(
                static function (CallableNode $node) {
                    return new CallableNodeRenderer(
                        $node
                    );
                }
            ),
            SectionBeginNode::class => new CallableNodeRendererFactory(
                function (SectionBeginNode $node) {
                    return new SectionBeginNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
            SectionEndNode::class => new CallableNodeRendererFactory(
                function (SectionEndNode $node) {
                    return new SectionEndNodeRenderer(
                        $node,
                        $this->templateRenderer
                    );
                }
            ),
        ];

        $nodeRendererFactories[DocumentNode::class] = new CallableNodeRendererFactory(
            function (DocumentNode $node) {
                return new DocumentNodeRenderer(
                    $node,
                    $this->templateRenderer,
                    $this->subFolder
                );
            }
        );

        $nodeRendererFactories[CodeNode::class] = new CallableNodeRendererFactory(
            function (CodeNode $node) {
                return new CodeNodeRenderer(
                    $node,
                    $this->templateRenderer,
                    $this->globalTemplatesPath
                );
            }
        );

        $nodeRendererFactories[SpanNode::class] = new CallableNodeRendererFactory(
            function (SpanNode $node) {
                return new SpanNodeRenderer(
                    $node->getEnvironment(),
                    $node,
                    $this->templateRenderer
                );
            }
        );

        return $nodeRendererFactories;
    }
}
