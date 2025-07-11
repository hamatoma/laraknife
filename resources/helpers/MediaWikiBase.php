<?php
namespace App\Helpers;

use App\Models\File;
use App\Models\Page;
use App\Http\Controllers\PageController;

// Macros:

class MediaWikiBase extends LayoutStatus
{
    protected ?string $inlineRegExpression;
    protected ?string $lineRegExpression;
    /// "fill": shows the default value "preview": shows the solutions "check": compares values with solutions
    protected ?string $clozeMode;
    public int $clozeErrors;
    protected ?array $clozeData;
    protected ?array $fieldnames;
    protected ?string $lastFieldname;
    protected bool $correctFieldnames;
    public string $corrections;
    function __construct()
    {
        parent::__construct();
        $this->inlineRegExpression = null;
        $this->lineRegExpression = null;
        $this->clozeMode = null;
        $this->clozeData = null;
        $this->clozeErrors = 0;
        $this->fieldnames = null;
        $this->correctFieldnames = false;
        $this->lastFieldname = null;
        $this->corrections = '';
    }
    /**
     * Returns the reference used in the href="<reference>" part of the link.
     *
     * @param string $title
     *            the title of the article
     * @param string $text
     *            the text of the article. Used if creation is needed
     * @return string the reference, e.g. "/page-showbyid/123"
     */
    function buildInternalLink(string $title, string $text = null): string
    {
        return $title;
    }
    /**
     * A unique fieldname is returned based on the last found correct fieldname.
     * @return string a unique fieldname
     */
    function buildNextFieldname(): string
    {
        $rc = null;
        if ($this->correctFieldnames) {
            if (count($this->fieldnames) == 0) {
                $rc = 'fld1A1';
            } else {
                $lastName = $this->fieldnames[count($this->fieldnames) - 1];
                if (preg_match('/(\d+)$/', $lastName, $matcher)) {
                    $no = intval($matcher[1]);
                    do {
                        $no++;
                        $rc = substr($lastName, 0, strlen($lastName) - strlen($matcher[1])) . strval($no);
                    } while (in_array($rc, $this->fieldnames));
                } else {
                    $no = 1;
                    do {
                        $no++;
                        $rc = $lastName . strval($no);
                    } while (in_array($rc, $this->fieldnames));
                }
                $this->corrections .= " $rc";
            }
            array_push($this->fieldnames, $rc);
        }
        return $rc;
    }
    /**
     * Tests all fieldnames of the page for uniqueness.
     * If a fieldname is already defined and $this->correctFieldname is set the fieldname is replaced by a unique name.
     * @param string $body IN/OUT: the markup source code to check. Will be changed on errors.
     */
    function checkFieldnames(string &$body)
    {
        $newBody = '';
        $start = 0;
        $lastEnd = 0;
        while (preg_match('/%field\(\w+/', $body, $matcher, PREG_OFFSET_CAPTURE, $start) > 0) {
            $fieldname = substr($matcher[0][0], 7);
            $offset = intval($matcher[0][1]) + 7;
            $start = $offset + strlen($fieldname);
            if (!in_array($fieldname, $this->fieldnames)) {
                array_push($this->fieldnames, $fieldname);
            } else {
                if ($this->correctFieldnames) {
                    $newName = $this->buildNextFieldname();
                } else {
                    // make a warning in front of "%field(..)":
                    $offset -= 7;
                    // a red exclamation symbol, warning, exclamation
                    $newName = 'U+x2757;U+x26A0;!!';
                    $fieldname = '';
                }
                $newBody .= substr($body, $lastEnd, $offset - $lastEnd) . $newName;
                $lastEnd = $offset + strlen($fieldname);
            }
        }
        if ($newBody !== '') {
            $body = $newBody . substr($body, $lastEnd);
        }
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
    public function setClozeParameters(string $clozeMode = "fill", ?array $clozeData = null)
    {
        $this->clozeMode = $clozeMode;
        $this->clozeData = $clozeData;
        if ($clozeMode === 'preview') {
            $this->fieldnames = [];
            $this->correctFieldnames = true;
        }
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
    /**
     * Converts a wiki markup text into HTML.
     * @param string $wikiText IN/OUT: the markup to convert. May be corrected.
     * @return string
     */
    function toHtml(string &$wikiText): string
    {
        if ($this->fieldnames !== null) {
            $this->checkFieldnames($wikiText);
        }
        $this->htmlBody = '';
        $lines = explode("\n", $wikiText);
        foreach ($lines as $ii => $line) {
            $linePrefix = '';
            if (($lineTrimmed = trim($line)) === '') {
                $this->writeParagraphEnd();
            } elseif (str_starts_with($line, '<nl>')) {
                $this->writeParagraphEnd();
                $this->htmlBody .= "<p class=\"lkn-empty-line\">&nbsp;</p>\n";
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
                } elseif ($lineTrimmed === '</pre>') {
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
                        if (strpos('=*#: -|@,', $linePrefix) === false) {
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
                                if (($rc = preg_match('/^(=+)\s*(.*)(\1)\s*$/', $line, $match)) !== false && count($match) > 2) {
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
    function writeInternalLink(string $link, ?string $text)
    {
        $html = null;
        $matcher = null;
        if (preg_match('&^file:(\d+)_(.*)[.](jpe?g|png|gif|svg])$&', $link, $matcher)){
            $id = intval($matcher[1]);
            $text = $matcher[2];
            if ( ($link = File::relativeFileLink($id)) == null){
                $link = '#';
            }
            $html = "<img src=\"$link\" alt=\"$text\" class=\"intern-media\" />";
        } elseif (preg_match('&^(upload):?/&', $link) && preg_match('/[.](jpe?g|png|gif|svg])$/i', $link)) {
            $link = preg_replace('&[\\/]\.\.[\/]&', '', $link);
            $link = 'upload' . preg_replace('%^[^/]+%', '', $link);
            if ($text == null) {
                $text = basename($link);
            } else {
                preg_match('%([^/]+)\.[^.]+$%', $link, $matcher);
                $text = $matcher[1];
            }
            $html = "<img src=\"/$link\" alt=\"$text\" class=\"intern-media\" />";
        } else if (str_starts_with($link, 'page:')) {
            $rest = substr($link, 5);
            // page:pretty/{id}
            // page:menu/{name}
            // page:help/{name}
            // page:byname/{name}/{pageType}
            if ($text == null) {
                $page = null;
                if (str_starts_with($rest, 'pretty/')) {
                    $page = Page::byId(intval(substr($rest, 7)));
                } else if (str_starts_with($rest, 'menu/')) {
                    $page = Page::byNameAndType(substr($rest, 5), 1141);
                } else if (str_starts_with($rest, 'help/')) {
                    $page = Page::byNameAndType(substr($rest, 5), 1142);
                } else if (str_starts_with($rest, 'byname/')) {
                    $parts = explode('/', substr($rest, 7));
                    if (count($parts) == 1) {
                        array_push($parts, 1144);
                    }
                    $page = Page::byNameAndType($parts[0], intval($parts[1]));
                }
                $text = $page != null ? $page->title : $link;
            }
            $link = "/page-show$rest";
        } elseif (strpos($link, '/') === false) {
            $title = $this->encodeWikiName($link);
            $page = Page::byTitle($link);
            if ($text == null && $page != null) {
                $text = $page->title;
            }
            if ($page == null) {
                if ($text == null) {
                    $text = $link;
                }
                $link = PageController::linkOfPageCreation($link);
            } else {
                if ($text == null) {
                    $text = $page->title;
                }
                $link = "/page-showbyid/$page->id";
            }
        } else {
            if ($text == null) {
                $text = $link;
            }
        }
        if ($html == null) {
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
            } elseif ($count >= 7 && $match[6] !== '') {
                $text = $count >= 8 ? substr($match[7], 1) : null;
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
