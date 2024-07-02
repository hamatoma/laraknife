<?php
namespace App\Helpers;

// Macros:

class MediaWikiBase extends LayoutStatus
{
    protected ?string $inlineRegExpression;
    protected ?string $lineRegExpression;
    /// "fill": shows the default value "preview": shows the solutions "check": compares values with solutions
    protected ?string $clozeMode;
    public int $clozeErrors;
    protected ?array $clozeData;
    function __construct()
    {
        parent::__construct();
        $this->inlineRegExpression = null;
        $this->lineRegExpression = null;
        $this->clozeMode = null;
        $this->clozeData = null;
        $this->clozeErrors = 0;
    }
    public function setClozeParameters(string $clozeMode = "fill", ?array $clozeData = null)
    {
        $this->clozeMode = $clozeMode;
        $this->clozeData = $clozeData;
    }
    /**
     * Returns the reference used in the href="<reference>" part of the link.
     *
     * @param string $title
     *            the title of the article
     * @param string $text
     *            the text of the article. Used if creation is needed
     * @return string the reference, e.g. "articleview?id=5"
     */
    function buildInternalLink(string $title, string $text = null): string
    {
        return $title;
    }
    function countRepeats(string $text, string $marker): int
    {
        $count = 0;
        $max = strlen($text);
        while ($count < $max && $text[$count] === $marker) {
            $count++;
        }
        return $count;
    }
    function decodeWikiName(string $name)
    {
        return urldecode($name);
    }
    function encodeHeadline($text)
    {
        $text = str_replace(
            ['%', ' ', '/', '?', '&'],
            ['%25', '%20', '%2f', '%3f', '%26', '?',],
            $text
        );
        return $text;
    }
    function encodeWikiName(string $name)
    {
        return urlencode($name);
    }
    public static function expandStarItems(string $text): string
    {
        $rc = preg_replace(
            [
                '/\*(\w[^*]+\w)\*/',
                '/\*-(\w[^*]+\w)-\*/',
                '/\*\+(\w[^*]+\w)\+\*/'
            ],
            [
                '%trans($1)%',
                '%del($1)%',
                '%add($1)%'
            ],
            $text
        );
        return $rc;
    }
    function finishPreBlock()
    {
        $this->htmlBody .= "</pre>\n";
        $this->preformatted = false;
    }
    function horizontalLine(int $width)
    {
        // # echo "GetType(): " . gettype($width);
        if (!isset($width) || strcmp(gettype($width), 'object') == 0) {
            $width = 2;
        }
        $this->htmlBody .= "<hr class=\"lkn-hrule-$width\">\n";
    }
    function specialMacrosToHtml(string $body): string
    {
        $pos = 0;
        $body = preg_replace_callback('%(\w+)\((.*?)\)%', function ($matches) {
            switch ($matches[1]) {
                case 'Date':
                    $rc = date('%Y.%m.%d');
                    break;
                case 'DateTime':
                    $rc = date('%Y.%m.%d %H:%M');
                    break;
                case 'mark':
                    $mode = count($matches) > 3 ? substr($matches[3], 1) : 'info';
                    $rc = "<span class=\"lkn-text-$mode\">$matches[2]</span>";
                    break;
                case 'add':
                    $rc = '<ins class="lkn-ins">' . (count($matches) >= 4 ? ($matches[2] . $matches[3]) : $matches[2]) . '</ins>';
                    break;
                case 'del':
                    $rc = '<del class="lkn-del">' . (count($matches) == 4 ? ($matches[2] . $matches[3]) : $matches[2]) . '</del>';
                    break;
                case 'field':
                    $name = $matches[2];
                    $value = '';
                    $size = 8;
                    if (count($matches) > 3) {
                        $parts = explode('|', substr($matches[3], 1));
                        $value = $parts[0];
                        if (count($parts) > 1) {
                            $size = $parts[1];
                        }
                    }
                    $rc = "<input class=\"lkn-field\" name=\"$name\" value=\"$value\" size=\"$size\">";
                    break;
                case 'icon':
                    $name = $matches[2];
                    $size = count($matches) < 4 ? 0 : intval(substr($matches[3], 1));
                    $class = $size == 0 ? '' : "lkn-icon-$size";
                    $rc = "<i class=\"$name $class\"></i>";
                    break;
                default:
                    $rc = $matches[0];
                    break;
            }
            return $rc;
        }, $body);
        $body = preg_replace('/U\+(x[\dA-F]+;)/', '&#\1', $body);
        return $body;
    }
    function startPreBlock()
    {
        $this->htmlBody .= "<pre>";
        $this->preformatted = true;
    }
    function toHtml(string $wiki_text): string
    {
        $this->htmlBody = '';
        $lines = explode("\n", $wiki_text);
        foreach ($lines as $ii => $line) {
            $linePrefix = '';
            if (($line_trimmed = trim($line)) === '') {
                $this->writeParagraphEnd();
            } elseif ($this->openTable && strncmp($line, '|}', 2) == 0) {
                $this->stopTable();
            } elseif (strncmp($line, '{|', 2) == 0) {
                $this->startTable(substr($line, 2));
            } else {
                $linePrefix = substr($line, 0, 1);
                if (strncmp($line, '<pre>', 5) == 0) {
                    $this->startPreBlock();
                    $rest = trim(substr($line, 5));
                    if ($rest !== '') {
                        $this->htmlBody .= htmlentities($rest) . "\n";
                    }
                } elseif ($line_trimmed === '</pre>') {
                    $this->finishPreBlock();
                } elseif ($this->preformatted) {
                    $this->htmlBody .= htmlentities($line) . "\n";
                } else {
                    if ($this->openTable && $linePrefix === '|') {
                        $linePrefix = '';
                        if (strncmp($line, '|+', 2) == 0) {
                            $this->tableCaption(substr($line, 2));
                            $line = '';
                        } elseif (strncmp($line, '|-', 2) == 0) {
                            $this->tableRow(substr($line, 2));
                            $line = '';
                        } elseif (strncmp($line, '|}', 2) == 0) {
                            $this->stopTable();
                            $line = '';
                        } else {
                            $line = $this->tableCol(trim(substr($line, 1)));
                            $linePrefix = '|';
                        }
                    } elseif ($this->openTable && $linePrefix === '!') {
                        $this->tableHeader(trim(substr($line, 1)));
                        $line = '';
                    }
                    if ($line !== '') {
                        if (strpos('=*#: -|@,', $linePrefix) === false){
                            $linePrefix = "\n";
                        }
                        if ($linePrefix != $this->prefixLastLine) {
                            $this->stopParagraph();
                        }
                        switch ($linePrefix) {
                            case ',':
                                $this->writeText(substr($line, 1));
                                $this->htmlBody .= "\n";
                                break;
                            case '=':
                                if (($rc = preg_match('/^(=+)\s*(.*)(\1)\s*$/', $line, $match)) !== false) {
                                    $this->writeHeader($match[2], strlen($match[1]));
                                } else {
                                    $this->writeLine($line);
                                }
                                break;
                            case '*':
                                $this->writeUList($line);
                                break;
                            case '#':
                                $this->writeOrderedList($line);
                                break;
                            case ':':
                                $this->writeIndent($line);
                                break;
                            case ' ':
                                $this->writeLine(substr($line, 1), true);
                                break;
                            case '-':
                                $this->writeHorizontalLine($line);
                                break;
                            case '|':
                                $this->writeLine($line, false);
                                break;
                            case '@':
                                if (str_starts_with($line, '@blockend')) {
                                    $this->stopSentence();
                                    $this->addHtml("</div>\n");
                                } elseif (str_starts_with($line, '@block')) {
                                    $this->stopSentence();
                                    $params = $this->checkAttributes(substr($line, 6));
                                    $this->addHtml($params !== '' ? "<div $params>" : '<div>');
                                } else {
                                    $this->writeLine(trim($line));
                                }
                                break;
                            default:
                                $this->writeLine(trim($line));
                                break;
                        }
                    }
                }
            }
            $this->prefixLastLine = $linePrefix;
        } // foreach
        $this->stopParagraph();
        return $this->htmlBody;
    }
    function writeExternLink(string $link, string $text)
    {
        if ($text == '')
            $text = $link;
        if (preg_match('/\.(jpg|png|gif|bmp)$/i', $link)) {
            $this->htmlBody .= '<img alt="' . $text . '" title="' . $text . '"src="' . $link . '">';
        } else {
            $this->htmlBody .= '<a href="' . $link . '">';
            $this->htmlBody .= htmlentities($text);
            $this->htmlBody .= '</a>';
        }
    }
    function writeHeader(string $line, int $level)
    {
        $this->stopSentence();
        $this->htmlBody .= "<h$level>";
        $this->writeText($line);
        $this->htmlBody .= "</h$level>";
    }
    function writeInternalLink(string $link, string $text)
    {
        if (str_starts_with($link, 'upload') && preg_match('/[.](jpe?g|png|gif|svg])$/i', $link)) {
            $link = preg_replace('&[\\/]\.\.[\/]&', '', $link);
            if (!$text) {
                preg_match('%([^/]+)\.[^.]+$%', $link, $matcher);
                $text = $matcher[1];
            }
            $html = "<img src=\"/$link\" alt=\"$text\" class=\"intern-media\" />";
        } else {
            if ($text === false) {
                $text = $link;
            }
            $html = "<a href=\"$link\">" . htmlentities($text) . '</a>';
        }
        $this->htmlBody .= $html;
    }
    function writeText($body)
    {
        $count = 0;
        if ($this->inlineRegExpression == null) {
            $this->inlineRegExpression =
                // Parenthesis 1: Prefix 2: complete search item
                // underlined, bold, italic, bold-italic
                "!(.*?)(__|'{2,5}" .
                // Extern-Link [http://heise.de Heise]
                // Parenthesis 3: URL 4: Protocoll 5: <blank>text
                '|\[((https?|ftp):\S+)( [^\]]+)?\]' .
                // Internal link: [[Server List|All servers]]
                // Parenthesis 6: Header 7: <|>text
                '|\[\[([^|\]]+)(\|[^\]]+)?\]\]' .
                // http link, ftp link
                // Parenthesis 8: Protokollname
                '|(https?|ftp):[^<>|\[\]\s]+' .
                // Tags with attributes:
                // Parenthesis 9: tag 10: attributes
                '|<(div|span|blockquote|code|pre)( [^>]+)?>' .
                // End tags:
                '|<\/(div|span|blockquote|code|pre)>' .
                // Tags without attributes:
                // Parenthesis 11: tag
                '|<\/?(ins|s|del|dfn|br|br ?/)>' .
                // end parenthesis 2
                ')!i';
        }
        $offset = 0;
        while (($rc = preg_match($this->inlineRegExpression, $body, $match, 0, $offset)) > 0) {
            if ($match[1] !== '') {
                $part = str_replace('<nowiki/>', '', $match[1]);
                $this->htmlBody .= $this->specialMacrosToHtml($part);
            }
            $count = count($match);
            if ($count > 3 && $match[3] !== '') {
                $this->writeExternLink($match[3], substr($match[5], 1));
            } elseif ($count > 7 && $match[6] !== '') {
                $text = $count >= 8 ? substr($match[7], 1) : basename($match[6]);
                $this->writeInternalLink($match[6], $text);
            } elseif ($count > 8 && $match[8] !== '') {
                $this->writeExternLink($match[2], '');
            } else {
                switch ($match[2]) {
                    case '__':
                        $this->handleEmphasis('u');
                        break;
                    case "'''''":
                        $this->handleEmphasis('x');
                        break;
                    case "'''":
                        $this->handleEmphasis('b');
                        break;
                    case "''":
                        $this->handleEmphasis('i');
                        break;
                    default:
                        $this->htmlBody .= $match[2];
                        break;
                } // switch
            }
            $offset += strlen($match[0]);
        }
        if ($offset < strlen($body)) {
            $part = str_replace('<nowiki/>', '', substr($body, $offset));
            $this->htmlBody .= $this->specialMacrosToHtml($part);
        }
    }
    function writeUList(string $line)
    {
        $count = $this->countRepeats($line, '*');
        $this->changeUListLevel($count);
        $this->writeText(trim(substr($line, $count)));
    }
    function writeOrderedList(string $line)
    {
        $count = $this->countRepeats($line, '#');
        $this->changeOrderedListLevel($count);
        $this->writeText(trim(substr($line, $count)));
    }
    function writeIndent(string $line)
    {
        $count = $this->countRepeats($line, ':');
        $this->changeIndentLevel($count);
        $this->writeText(substr($line, $count));
    }
    function writeHorizontalLine(string $line)
    {
        $count = $this->countRepeats($line, '-');
        if ($count < 4) {
            $this->writeLine($line);
        } else {
            $this->horizontalLine($count - 3);
        }
    }
    function writeParagraphEnd()
    {
        $this->stopParagraph();
    }
    function writeLine(string $line, bool $withNewline = true)
    {
        if ($this->prefixLastLine == ' ') {
            $this->htmlBody .= "<br>";
        }
        $this->startParagraph();
        $this->writeText($line);
        if ($withNewline) {
            $this->htmlBody .= "\n";
        }
    }
}
class LayoutStatus
{
    protected $emphasisStack; // String: [ubi]* ; underline bold italic
    protected $indentLevel;
    protected $orderedListLevel;
    protected $uListLevel;
    protected $openTable;
    protected $openParagraph;
    protected $preformatted;
    protected $htmlBody;
    protected $prefixLastLine;
    protected $inTable;
    protected $rowNo;
    protected $colNo;
    protected $prefixLastCol;
    function __construct()
    {
        $this->emphasisStack = "";
        $this->indentLevel = $this->uListLevel = $this->orderedListLevel = 0;
        $this->rowNo = $this->colNo = 0;
        $this->openTable = $this->openParagraph = $this->preformatted = false;
        $this->prefixLastLine = '';
        $this->prefixLastCol = '';
    }
    function addHtml(string $part)
    {
        $this->htmlBody .= $part;
    }
    function changeListLevel(int $current, int &$level, string $tag, string $subTag)
    {
        $this->stopSentence();
        /*
         * c=0 c > l: <ul><li> x1
         * c>0 c > l: <ul><li> x21
         * c = l: </li><li> x22
         * c>0 c < l: </li></ul></li><li> x2
         * c = l: </li> <li>
         * c=0 c < l: </li></ul>
         *
         * c>0 c < l: </li></ul></li><li> x2
         *
         */
        if ($current > $level) {
            while ($current > $level) {
                $this->htmlBody .= "\n<$tag><$subTag>";
                $level++;
            }
        } else if ($current < $level) {
            while ($current < $level) {
                $this->htmlBody .= "</$subTag>\n</$tag>";
                --$level;
            }
            if ($level == 0) {
                $this->htmlBody .= "\n";
            }
            if ($current > 0) {
                $this->htmlBody .= "\n</$subTag><$subTag>";
            }
        } else if ($current > 0) {
            $this->htmlBody .= "</$subTag>\n<$subTag>";
        }
    }
    function changeIndentLevel(int $currentLevel)
    {
        if ($currentLevel !== $this->indentLevel || $currentLevel > 0) {
            if ($this->indentLevel === $currentLevel && $currentLevel > 0) {
                $this->htmlBody .= "</dd>\n<dd>";
            }
            while ($this->indentLevel < $currentLevel) {
                if ($currentLevel > 1) {
                    $this->htmlBody .= "\n";
                }
                $this->htmlBody .= '<dl><dd>';
                ++$this->indentLevel;
            }
            while ($this->indentLevel > $currentLevel) {
                $this->htmlBody .= "</dd></dl>\n";
                --$this->indentLevel;
            }
        }
    }
    function changeOrderedListLevel(int $val)
    {
        $this->changeListLevel($val, $this->orderedListLevel, 'ol', 'li');
    }
    function changeUListLevel(int $level)
    {
        $this->changeListLevel($level, $this->uListLevel, 'ul', 'li');
    }
    function checkAttributes(string $attributes)
    {
        if (strcspn($attributes, '<>') !== strlen($attributes)) {
            $attributes = '';
        } else {
            $attributes = trim($attributes);
        }
        return $attributes;
    }
    function finishCol()
    {
        switch ($this->prefixLastCol) {
            case '|':
                $this->stopParagraph();
                $this->htmlBody .= '</td>';
                break;
            case '!':
                $this->stopParagraph();
                $this->htmlBody .= '</th>';
                break;
            default:
                break;
        }
        $this->prefixLastCol = '';
    }
    function handleEmphasis(string $type)
    {
        if (($pos = strpos($this->emphasisStack, $type)) !== false) {
            $this->popEmphasis($type);
        } else {
            $this->pushEmphasis($type);
        }
    }
    function popEmphasis(string $type)
    {
        $pos = strrpos($this->emphasisStack, $type);
        if ($pos !== false) {
            for ($ii = strlen($this->emphasisStack) - 1; $ii >= $pos; $ii--) {
                $type = substr($this->emphasisStack, $ii, 1);
                $this->htmlBody .= $type == 'x' ? '</i></b>' : "</$type>";
            }
            $this->emphasisStack = substr($this->emphasisStack, 0, $pos);
        }
    }
    function pushEmphasis(string $type)
    {
        $this->emphasisStack .= $type;
        if ($type == 'x') {
            $this->htmlBody .= '<b><i>';
        } else {
            $this->htmlBody .= '<' . $type . '>';
        }
    }
    function startParagraph()
    {
        if (!$this->openParagraph) {
            $this->openParagraph = true;
            if (!$this->openTable) {
                $this->htmlBody .= '<p>';
            }
        }
    }
    function startTable(string $attributes)
    {
        if (!$this->openTable) {
            $this->stopSentence();
            $this->rowNo = $this->colNo = 0;
            $attributes = $this->checkAttributes($attributes);
            $this->openTable = true;
            $this->htmlBody .= $attributes === '' ? "<table>\n" : "<table $attributes>";
            $this->prefixLastCol = '';
        }
    }
    function stopParagraph()
    {
        $this->changeUListLevel(0);
        $this->changeOrderedListLevel(0);
        $this->changeIndentLevel(0);
        if ($this->openParagraph) {
            $this->openParagraph = false;
            if (!$this->openTable) {
                $this->htmlBody .= "</p>\n";
            }
        }
    }
    function stopSentence()
    {
        while ($this->emphasisStack !== "")
            $this->popEmphasis($this->topEmphasis());
    }
    function stopTable()
    {
        $this->finishCol();
        if ($this->openTable) {
            $this->openTable = false;
            if ($this->rowNo > 0) {
                $this->htmlBody .= "</tr>";
            }
            $this->htmlBody .= "</table>\n";
        }
    }
    function tableCaption(string $caption)
    {
        $this->stopParagraph();
        $this->tagPair('caption', $caption);
    }
    function tableCol(string $line)
    {
        if (strpos($line, '||') !== false) {
            $lines = explode('||', $line);
            foreach ($lines as $line) {
                $line2 = $this->tableCol($line);
                $this->writeLine($line2, false);
            }
            $rc = '';
        } else {
            $this->finishCol();
            if (($pos = strpos($line, '|')) !== false) {
                $attributes = $this->checkAttributes(substr($line, 0, $pos));
                $line = substr($line, $pos + 1);
            } else {
                $attributes = '';
            }
            $this->htmlBody .= $attributes === '' ? '<td>' : "<td $attributes>";
            $this->prefixLastCol = '|';
            $rc = $line;
        }
        return $rc;
    }
    function tableHeader(string $line)
    {
        $this->finishCol();
        if ($this->colNo++ == 0 && $this->rowNo == 0) {
            $this->tableRow('');
        }
        if (strpos($line, '!!') !== false) {
            $lines = explode('!!', $line);
            foreach ($lines as $line) {
                $this->tableHeader($line);
            }
        } else {
            if (strpos($line, '|') === false) {
                $tag = '<th>';
            } else {
                $parts = explode('|', $line, 2);
                $tag = '<th ' . $this->checkAttributes($parts[0]) . '>';
                $line = $parts[1];
            }
            $this->htmlBody .= $tag; 
            $this->writeText($line);
        }
        $this->prefixLastCol = '!';
    }
    function tableRow(string $attributes)
    {
        if ($this->rowNo++ != 0) {
            $this->finishCol();
            $this->htmlBody .= "</tr>\n";
        }
        $attributes = $this->checkAttributes($attributes);
        $this->htmlBody .= $attributes === '' ? '<tr>' : "<tr $attributes>";
        $this->colNo = 0;
    }
    function tagPair(string $tag, string $body)
    {
        $this->htmlBody .= "<" . $tag . ">";
        $this->writeText($body);
        $this->stopSentence();
        $this->htmlBody .= "</" . $tag . ">\n";
    }
    function topEmphasis(): string
    {
        return substr($this->emphasisStack, strlen($this->emphasisStack) - 1);
    }
}
