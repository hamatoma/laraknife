<?php
namespace App\Helpers;

class MediaWiki
{
    // p: paragraph ul: unordered list ol: ordered list c: code (<pre>) h: headline i: indented
    protected $blockType;
    protected $openBlock;
    protected $contents;
    protected $listStack;
    public function __construct()
    {
        $this->blockType = '?';
        $this->openBlock = '';
        $this->contents = '';
        // Open (un-)/ordered list items, e.g. "uuo"
        $this->listStack = '';
    }
    protected function addContents(string $text, ?string $prefix = null, ?string $suffix = null)
    {
        if ($prefix != null) {
            $this->contents .= $prefix;
        }
        $this->contents .= $text;
        if ($suffix != null) {
            $this->contents .= $suffix;
        }
    }
    protected function changeListLevel(int $to, string $type)
    {
        /*
<ul><li>abc
  <!-- level++ -->
  <ul><li>point1</li>
    <li>point1</li>
    <li>point2 <!-- level-- --></li>
  </ul></li>
<li>def
  <ul><li>xyz</li>
    <li>yz</li>
  </ul></li>
</ul>
         */
        $from = strlen($this->listStack);
        if ($from === $to) {
            $this->addContents("</li>\n<li>");
        } else {
            if ($from < $to) {
                // level++
                $tag = str_ends_with($this->listStack, 'u') ? 'ul' : 'ol';
                $this->addContents("<$tag><li>");
                $this->listStack .= $type[0];
            } else {
                // level--
                while ($from > $to && ! empty($this->listStack)) {
                    $end = strlen($this->listStack) > 1 ? '</li>' : '';
                    $tag = str_ends_with($this->listStack, 'u') ? 'ul' : 'ol';
                    $this->addContents("</li></$tag>$end\n");
                    $this->listStack = substr($this->listStack, 0, strlen($this->listStack) - 1);
                    $from--;
                }
                if ($to != 0){
                    $this->addContents("\n<li>");
                }
            }
        }
    }
    protected function changeBlockType(string $type, int $level = 0)
    {
        switch ($this->blockType) {
            case 'c':
                if ($type !== 'c') {
                    $this->addContents($this->handleBlock($this->openBlock), "<pre>\n", "\n</pre>\n");
                    $this->openBlock = '';
                }
                break;
            case 'p':
                if ($type !== 'p') {
                    $this->addContents($this->handleBlock($this->openBlock), '<p>', "</p>\n");
                    $this->openBlock = '';
                }
                break;
            case 'ul':
            case 'ol':
                if (!str_ends_with($this->blockType, 'l')) {
                    $this->changeListLevel(0, $type);
                } else {
                    $this->changeListLevel($level, $type);
                }
                break;
            default:
            case 'h':
                break;

        }
        switch ($type) {
            case 'c':
            case 'p':
                if ($this->blockType !== $type) {
                    $this->openBlock = '';
                }
                break;
            case 'ul':
            case 'ol':
                if ($this->blockType !== $type) {
                    $this->addContents("<$type><li>");
                    $this->listStack = substr($type, 0, 1);
                }
                break;
            case 't':
                break;
        }
        $this->blockType = $type;
    }
    public function findLevel(string &$text): string
    {
        $ix = 0;
        $char = $text[0];
        $length = strlen($text);
        while ($ix < $length && $text[$ix] === $char) {
            $ix++;
        }
        return $ix;
    }
    protected function handleBlock(string &$text): string
    {
        $rc = preg_replace(
            [
                "/'''''(.+?)'''''/",
                "/'''(.+?)'''/",
                "/''(.+?)''/",
                '/\[\[(.+?)\|(.+?)\]\]/',
                '/\[\[(.+?)\]\]/',
                '/(https?:\S+)/'
            ],
            [
                '<b><i>$1</i></b>',
                '<b>$1</b>',
                '<i>$1</i>',
                '<a href="$1">$2</a>',
                '<a href="$1">$1</a>',
                '<a href="$1">$1</a>'
            ],
            $text
        );
        $rc = preg_replace_callback(
            '/%trans\((.*?)(\|.*?)?\)%/',
            function ($hit) {
                $text = $hit[1];
                $info = count($hit) == 2 ? $text : substr($hit[2], 1);
                $rc = "<i><b data-toggle=\"tooltip\" data-placement=\"top\" title=\"$info\">$text</b></i>";
                return $rc;
            },
            $rc
        );
        return $rc;
    }
    protected function title(string &$line)
    {
        $level = $this->findLevel($line);
        $block = trim(substr($line, $level, strlen($line) - 2 * $level));
        $block = $this->handleBlock($block);
        $this->addContents($block, "<h$level>", "</h$level\n>");
    }
    public function toHtml(string $text): string
    {
        $pos = 0;
        $this->block = '';
        $again = true;
        while ($again) {
            if (($end = strpos($text, "\n", $pos)) === false) {
                $text2 = substr($text, $pos);
                $again = false;
            } else {
                $text2 = substr($text, $pos, $end - $pos + 1);
                $text2 = rtrim($text2);
                $pos = $end + 1;
            }
            if (empty($text2)) {
                if (!empty($this->openBlock)) {
                    if ($this->blockType == 'p') {
                        $this->changeBlockType('p');
                        $this->openBlock .= ' ' . $text2;
                    } else {
                        $this->changeBlockType('p');
                        $this->openBlock = '';
                    }
                }
            } else {
                $firstChar = $text2[0];
                switch ($firstChar) {
                    case '*':
                    case '#':
                        $level = $this->findLevel($text2);
                        $text2 = trim(substr($text2, $level));
                        $this->changeBlockType($firstChar === '*' ? 'ul' : 'ol', $level);
                        $this->addContents($this->handleBlock($text2));
                        break;
                    case '=':
                        if (str_ends_with($text2, '=')) {
                            $this->title($text2);
                        } else {
                            $this->changeBlockType('h');
                            $this->openBlock .= ' ' . $text2;
                        }
                        break;
                    case ' ':
                        $this->changeBlockType('c');
                        $this->openBlock .= ' ' . $text2;
                        break;
                    case '-':
                        if (str_starts_with($text2, '---')) {
                            $this->changeBlockType('?');
                            $this->addContents("<hr>\n");
                        } else {
                            $this->changeBlockType('p');
                            $this->openBlock .= ' ' . $text2;
                        }
                        break;
                    default:
                        $this->changeBlockType('p');
                        $this->openBlock .= ' ' . $text2;
                        break;

                }
            }
        }
        $this->changeBlockType('?');
        $rc = $this->contents;
        return $rc;
    }
}
