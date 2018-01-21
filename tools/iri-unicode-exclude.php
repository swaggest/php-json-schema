<?php

/* Remove certain exceptions:
** U+0640 ARABIC TATWEEL
** U+07FA NKO LAJANYALAN
** U+302E HANGUL SINGLE DOT TONE MARK
** U+302F HANGUL DOUBLE DOT TONE MARK
** U+3031 VERTICAL KANA REPEAT MARK
** U+3032 VERTICAL KANA REPEAT WITH VOICED SOUND MARK
** ..
** U+3035 VERTICAL KANA REPEAT MARK LOWER HALF
** U+303B VERTICAL IDEOGRAPHIC ITERATION MARK
*/
function certainExceptions()
{
    $u = '\u0640 \u07FA \u302E \u302F \u3031 \u3032 \u3033 \u3034 \u3035 \u303B';
    $s = json_decode('"' . $u . '"');
    $ue = explode(' ', $u);
    foreach (explode(' ', $s) as $ci => $char) {
        $esc = '';
        for ($i = 0; $i < strlen($char); ++$i) {
            $esc .= '\\x' . strtoupper(dechex(ord($char[$i])));
        }
        echo '"' . $esc, '" =>  \'' . $ue[$ci], "',\n";
    }
}

function excludes()
{
    certainExceptions();
    echo '// Remove characters used for archaic Hangul (Korean) - \p{HST=L} and \p{HST=V}', "\n";
    echo '// as per http://unicode.org/Public/UNIDATA/HangulSyllableType.txt', "\n";
    makeRangeFromPreg('\x{1100}-\x{115F}');
    makeRangeFromPreg('\x{A960}-\x{A97C}');
    makeRangeFromPreg('\x{1160}-\x{11A7}');
    makeRangeFromPreg('\x{D7B0}-\x{D7C6}');

    echo '// Remove three blocks of technical or archaic symbols.', "\n";
    echo '// \p{block=Combining_Diacritical_Marks_For_Symbols}', "\n";
    makeRangeFromPreg('\x{20D0}-\x{20FF}');
    echo '// \p{block=Musical_Symbols}', "\n";
    makeRangeFromPreg('\x{1D100}-\x{1D1FF}');
    echo '// \p{block=Ancient_Greek_Musical_Notation}', "\n";
    makeRangeFromPreg('\x{1D200}-\x{1D24F}');
}

function makeRangeFromPreg($s)
{
    list($start, $end) = explode('-', $s);
    $startHex = substr($start, 3, -1);
    $endHex = substr($end, 3, -1);
    $startDec = hexdec($startHex);
    $endDec = hexdec($endHex);
    for ($j = $startDec; $j <= $endDec; ++$j) {
        $u = '\u' . dechex($j);
        $char = json_decode('"' . $u . '"');
        $esc = '';

        for ($i = 0; $i < strlen($char); ++$i) {
            $esc .= '\\x' . strtoupper(dechex(ord($char[$i])));
        }
        echo '"' . $esc, '" =>  \'' . $u, "',\n";
    }
}

excludes();