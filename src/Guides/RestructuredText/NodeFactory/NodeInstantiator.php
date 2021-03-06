<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\NodeFactory;

use Doctrine\Common\EventManager;
use InvalidArgumentException;
use phpDocumentor\Guides\Nodes\Node;
use phpDocumentor\Guides\Nodes\NodeTypes;
use phpDocumentor\Guides\Renderers\NodeRendererFactory;
use function in_array;
use function is_subclass_of;
use function sprintf;

class NodeInstantiator
{
    /** @var string */
    private $type;

    /** @var string */
    private $className;

    /** @var NodeRendererFactory|null */
    private $nodeRendererFactory;

    /** @var EventManager|null */
    private $eventManager;

    public function __construct(
        string $type,
        string $className,
        ?NodeRendererFactory $nodeRendererFactory = null,
        ?EventManager $eventManager = null
    ) {
        if (! in_array($type, NodeTypes::NODES, true)) {
            throw new InvalidArgumentException(
                sprintf('Node type %s is not a valid node type.', $type)
            );
        }

        if (! is_subclass_of($className, Node::class)) {
            throw new InvalidArgumentException(
                sprintf('%s class is not a subclass of %s', $className, Node::class)
            );
        }

        $this->type                = $type;
        $this->className           = $className;
        $this->nodeRendererFactory = $nodeRendererFactory;
        $this->eventManager        = $eventManager;
    }

    public function getType() : string
    {
        return $this->type;
    }

    /**
     * @param mixed[] $arguments
     */
    public function create(array $arguments) : Node
    {
        /** @var Node $node */
        $node = new $this->className(...$arguments);

        if ($this->nodeRendererFactory !== null) {
            $node->setNodeRendererFactory($this->nodeRendererFactory);
        }

        if ($this->eventManager !== null) {
            $node->setEventManager($this->eventManager);
        }

        return $node;
    }
}
