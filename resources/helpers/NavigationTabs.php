<?php
namespace App\Helpers;

/**
 * Stores the info about a bootstrap 5 navigation tab.
 */
class NavigationTabs {
    public array $items;
    public TabItem $activeItem;
    /**
     * Constructor.
     * @param array $items a list of strings. Each string has two parts separated by a ';': text, link
     * @param int $indexActive the index of the active item in $items
     */
    public function __construct(array $items, int $indexActive=0){
        $this->items = [];
        foreach($items as &$item){
            $parts = explode(';', $item);
            $item2 = new TabItem($parts[0], $parts[1]);
            array_push($this->items, $item2);
        }
        if ($indexActive < 0 || $indexActive >= count($this->items)){
            $indexActive = 0;
        }
        $this->activeItem = $this->items[$indexActive];
    }
    public function active(TabItem $item): string{
        $rc = '';
        if ($item === $this->activeItem){
            $rc = ' active';
        }
        return $rc;
    }
    public function activeClass(TabItem $item): string{
        $rc = '';
        if ($item === $this->activeItem){
            $rc = ' lkn-nav-item-active';
        }
        return $rc;
    }
    public function ariaCurrent(TabItem $item): string{
        $rc = '';
        if ($item === $this->activeItem){
            $rc = ' aria-current="page"';
        }
        return $rc;
    }
}
class TabItem {
    public string $text;
    public string $link;
    public bool $disabled;
    public function __construct(string $text, string $link, bool $disabled=false){
        $this->text = $text;
        $this->link = $link;
        $this->disabled = $disabled;
    }
}
