<?php

namespace User\View\Helper;

use Zend\View\Helper\AbstractHelper;

// This view helper class displays breadcrumbs.
class Breadcrumbs extends AbstractHelper
{
    // Array of items.
    private $items = [];

    // Constructor.
    public function __construct($items = [])
    {
        $this->items = $items;
    }

    // Sets the items.
    public function setItems($items)
    {
        $this->items = $items;
    }

    // Renders the breadcrumbs.
    public function render()
    {
        if (count($this->items) == 0) {
            return ''; // Do nothing if there are no items.
        }

        // Resulting HTML code will be stored in this var
        $result = '<ol class="breadcrumb">';

        // Get item count
        $itemCount = count($this->items);

        $itemNum = 1; // item counter

        // Walk through items
        foreach ($this->items as $label => $link) {
            // Make the last item inactive
            $isActive = ($itemNum == $itemCount ? true : false);

            // Render current item
            $result .= $this->renderItem($label, $link, $isActive);

            // Increment item counter
            $itemNum++;
        }

        $result .= '</ol>';

        return $result;
    }

    // Renders an item.
    protected function renderItem($label, $link, $isActive)
    {
        $result = $isActive ? '<li class="active">' : '<li>';

        if (!$isActive) {
            $result .= '<a href="' . $link . '">' . $label . '</a>';
        } else {
            $result .= $label;
        }

        $result .= '</li>';

        return $result;
    }
}
