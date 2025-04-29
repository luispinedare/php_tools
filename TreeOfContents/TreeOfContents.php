<?php
// Escape HTML attributes safely
function escape($value) {
    return htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

// Generate an <img> tag safely
function buildImage($src, $class = null, $id = null, $alt = null, $style = null) {
    $html = '<img src="' . escape($src) . '"';

    if ($class) {
        $html .= ' class="' . escape($class) . '"';
    }
    if ($id) {
        $html .= ' id="' . escape($id) . '"';
    }
    if ($alt) {
        $html .= ' alt="' . escape($alt) . '"';
    }
    if ($style) {
        $html .= ' style="' . escape($style) . '"';
    }

    $html .= '>';
    return $html;
}

// Main function to build HTML tree
function TreeOfContents($TAG, $CLASS = null, $CONTENT = null, $LINK = null, $IMAGE = null, $SRC = null, $ID = null, $ALT = null, $STYLE = null) {
    $html = '';

    $safeTag = strtolower($TAG); // Sanitize tag name (optionally you could whitelist allowed tags)

    if ($safeTag === "div") {
        $html .= '<div';
        if ($CLASS) $html .= ' class="' . escape($CLASS) . '"';
        if ($ID) $html .= ' id="' . escape($ID) . '"';
        if ($ALT) $html .= ' alt="' . escape($ALT) . '"';
        if ($STYLE) $html .= ' style="' . escape($STYLE) . '"';
        $html .= '>';

        if (is_array($CONTENT)) {
            foreach ($CONTENT as $element) {
                $html .= TreeOfContents(
                    $element['TAG'] ?? '',
                    $element['CLASS'] ?? null,
                    $element['CONTENT'] ?? null,
                    $element['LINK'] ?? null,
                    $element['IMAGE'] ?? null,
                    $element['SRC'] ?? null,
                    $element['ID'] ?? null,
                    $element['ALT'] ?? null,
                    $element['STYLE'] ?? null
                );
            }
        }

        $html .= '</div>';

    } elseif ($safeTag === "img") {
        $html .= buildImage($SRC, $CLASS, $ID, $ALT, $STYLE);

    } elseif ($safeTag === "a") {
        $html .= '<a href="' . escape($LINK) . '"';
        if ($CLASS) $html .= ' class="' . escape($CLASS) . '"';
        if ($ID) $html .= ' id="' . escape($ID) . '"';
        if ($ALT) $html .= ' alt="' . escape($ALT) . '"';
        if ($STYLE) $html .= ' style="' . escape($STYLE) . '"';
        $html .= '>';

        if ($CONTENT) {
            $html .= escape($CONTENT);
        } elseif ($IMAGE && is_array($IMAGE)) {
            $html .= buildImage(
                $IMAGE['SRC'] ?? '',
                $IMAGE['CLASS'] ?? null,
                $IMAGE['ID'] ?? null,
                $IMAGE['ALT'] ?? null,
                $IMAGE['STYLE'] ?? null
            );
        }

        $html .= '</a>';

    } else {
        // Generic tag
        $html .= '<' . escape($safeTag);
        if ($CLASS) $html .= ' class="' . escape($CLASS) . '"';
        if ($ID) $html .= ' id="' . escape($ID) . '"';
        if ($ALT) $html .= ' alt="' . escape($ALT) . '"';
        if ($STYLE) $html .= ' style="' . escape($STYLE) . '"';
        $html .= '>' . escape($CONTENT) . '</' . escape($safeTag) . '>';
    }

    return $html;
}
?>