<?php
/*
 * Wolf CMS - Content Management Simplified. <http://www.wolfcms.org>
 * Copyright (C) 2010 Martijn van der Kleijn <martijn.niji@gmail.com>
 *
 * This file is part of Wolf CMS. Wolf CMS is licensed under the GNU GPLv3 license.
 * Please see license.txt for the full license text.
 */

/**
 * @package Models
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @copyright Martijn van der Kleijn, 2010
 * @license http://www.gnu.org/licenses/gpl.html GPLv3 License
 */

/**
 * Generic Node model.
 *
 * First version for future feature set.
 *
 * @author Martijn van der Kleijn <martijn.niji@gmail.com>
 * @since Wolf version 0.7.0
 */
class Node extends Record {

    // Static variables used to store dynamic methods
    protected static $_methods = array();
    protected static $_static_methods = array();

    /**
     * Allows someone to use a dynamic method that was registered with registerMethod().
     *
     * This is for instance specific methods, for class level methods, __callStatic is used.
     *
     * @see Node::registerMethod()
     */
    public function __call($method, $arguments) {
        if(isset(self::$_methods[$method]))
            call_user_func(self::$_methods[$method], array_unshift($arguments, $this));
        else
            throw new Exception('Unknown dynamic method: '.$method);
    }
    
    /**
     * Allows someone to use a dynamic method that was registered with registerMethod().
     *
     * PHP 5.3+ only.
     * This is for class level methods, for instance level methods, __call is used.
     *
     * @see Node::registerMethod()
     */
    public static function __callStatic($method, $arguments) {
        if(isset(self::$_static_methods[$method]))
            call_user_func(self::$_static_methods[$method], array_unshift($arguments, $this));
        else
            throw new Exception('Unknown dynamic method: '.$method);
    }

    /**
     * Registers a new dynamic method.
     *
     * Dynamic methods allow plugins or other components to define a new method at run
     * time for the Node class as well as derivitives thereof. These methods can then be
     * called similar to normal methods.
     *
     * @todo Add code sample to phpdoc
     *
     * NOTE: Class level (static) methods can only be used starting PHP 5.3.
     */
    public static function registerMethod($method, $function, $static = false) {
        if ($static === true) {
            self::$_static_methods[$method] = $function;
            return true;
        }
        else {
            self::$_methods[$method] = $function;
            return true;
        }
        
        return false;
    }


    /**
     * Intended to eventually replace like-wise names JS function from wolf.js
     * 
     * Note: this function might undergo a name change in future...
     *
     * @param type $string
     * @return type 
     */
    public static function toSlug($string) {
        global $UTF8_ROMANIZATION;
        $string = trim($string);
        
        /* TEST to check if we need to do nothing and simply return the string
        // Test for non western characters that we support
        $roman = array_keys($UTF8_ROMANIZATION);
        $roman = implode('', $roman);

        $test = '([a-z]|[A-Z]|[0-9])';
        $test = preg_match($test, $string, $matches);
        
        if ($test === 0 || false === $test) {
            return $string;
        }
         * 
         */

        $string = strtr($string,$UTF8_ROMANIZATION);                    // Romanize
        $string = strtolower(trim($string));                            // Lowercase
        $string = preg_replace('/[^-a-z0-9~\s\.:;+=_]/', '', $string);  // Remove certain characters
        $string = preg_replace('/[\s\.:;=+]+/', '-', $string);          // Replace characters by '-'

        return preg_replace('/[-]+/', '-', $string);
    }
}



/**
 * Romanization lookup table
 *
 * This lookup tables provides a way to transform strings written in a language
 * different from the ones based upon latin letters into plain ASCII.
 *
 * Please note: this is not a scientific transliteration table. It only works
 * oneway from nonlatin to ASCII and it works by simple character replacement
 * only. Specialities of each language are not supported.
 * 
 * This code was taken from the DokuWiki project.
 *
 * @author Andreas Gohr <andi@splitbrain.org>
 * @author Vitaly Blokhin <vitinfo@vitn.com>
 * @link   http://www.uconv.com/translit.htm
 * @author Bisqwit <bisqwit@iki.fi>
 * @link   http://kanjidict.stc.cx/hiragana.php?src=2
 * @link   http://www.translatum.gr/converter/greek-transliteration.htm
 * @link   http://en.wikipedia.org/wiki/Royal_Thai_General_System_of_Transcription
 * @link   http://www.btranslations.com/resources/romanization/korean.asp
 * @author Arthit Suriyawongkul <arthit@gmail.com>
 * @author Denis Scheither <amorphis@uni-bremen.de>
 */
global $UTF8_ROMANIZATION;
if(empty($UTF8_ROMANIZATION)) $UTF8_ROMANIZATION = array(
  // scandinavian - differs from what we do in deaccent
  'å'=>'a','Å'=>'A','ä'=>'a','Ä'=>'A','ö'=>'o','Ö'=>'O',
    
  // various accents - added by mvdkleijn
  'á'=>'a','à'=>'a','â'=>'a','ą'=>'a',
  'ć'=>'c','č'=>'c','ç'=>'c','ц'=>'c',
  'д'=>'d','đ'=>'d','ď'=>'d',
  'é'=>'e','è'=>'e','ê'=>'e','ë'=>'e','ę'=>'e','ě'=>'e',
  'ф'=>'f',
  'г'=>'g','ѓ'=>'g',
  'í'=>'i','î'=>'i','ï'=>'i','и'=>'i',
  'й'=>'j',
  'к'=>'k',
  'ł'=>'l','л'=>'l',
  'м'=>'m',
  'ñ'=>'n','ń'=>'n','ň'=>'n',
  'ó'=>'o','ô'=>'o','ó'=>'o',
  'п'=>'p',
  'ú'=>'u','ù'=>'u','û'=>'u','ů'=>'u',
  'ř'=>'r',
  'š'=>'s','ś'=>'s',
  'ť'=>'t','т'=>'t',
  'в'=>'v',
  'ý'=>'y','ы'=>'y',
  'ž'=>'z','ż'=>'z','ź'=>'z','з'=>'z',
  'ä'=>'ae','æ'=>'ae',
  'ч'=>'ch',
  'ö'=>'oe','ø'=>'oe',
  'ü'=>'ue',
  'ш'=>'sh',
  'щ'=>'shh',
  'ß'=>'ss',
  'å'=>'aa',
  'я'=>'ya',
  'ю'=>'yu',
  'ж'=>'zh',

  //russian cyrillic
  'а'=>'a','А'=>'A','б'=>'b','Б'=>'B','в'=>'v','В'=>'V','г'=>'g','Г'=>'G',
  'д'=>'d','Д'=>'D','е'=>'e','Е'=>'E','ё'=>'jo','Ё'=>'Jo','ж'=>'zh','Ж'=>'Zh',
  'з'=>'z','З'=>'Z','и'=>'i','И'=>'I','й'=>'j','Й'=>'J','к'=>'k','К'=>'K',
  'л'=>'l','Л'=>'L','м'=>'m','М'=>'M','н'=>'n','Н'=>'N','о'=>'o','О'=>'O',
  'п'=>'p','П'=>'P','р'=>'r','Р'=>'R','с'=>'s','С'=>'S','т'=>'t','Т'=>'T',
  'у'=>'u','У'=>'U','ф'=>'f','Ф'=>'F','х'=>'x','Х'=>'X','ц'=>'c','Ц'=>'C',
  'ч'=>'ch','Ч'=>'Ch','ш'=>'sh','Ш'=>'Sh','щ'=>'sch','Щ'=>'Sch','ъ'=>'',
  'Ъ'=>'','ы'=>'y','Ы'=>'Y','ь'=>'','Ь'=>'','э'=>'eh','Э'=>'Eh','ю'=>'ju',
  'Ю'=>'Ju','я'=>'ja','Я'=>'Ja',
  // Ukrainian cyrillic
  'Ґ'=>'Gh','ґ'=>'gh','Є'=>'Je','є'=>'je','І'=>'I','і'=>'i','Ї'=>'Ji','ї'=>'ji',
  // Georgian
  'ა'=>'a','ბ'=>'b','გ'=>'g','დ'=>'d','ე'=>'e','ვ'=>'v','ზ'=>'z','თ'=>'th',
  'ი'=>'i','კ'=>'p','ლ'=>'l','მ'=>'m','ნ'=>'n','ო'=>'o','პ'=>'p','ჟ'=>'zh',
  'რ'=>'r','ს'=>'s','ტ'=>'t','უ'=>'u','ფ'=>'ph','ქ'=>'kh','ღ'=>'gh','ყ'=>'q',
  'შ'=>'sh','ჩ'=>'ch','ც'=>'c','ძ'=>'dh','წ'=>'w','ჭ'=>'j','ხ'=>'x','ჯ'=>'jh',
  'ჰ'=>'xh',
  //Sanskrit
  'अ'=>'a','आ'=>'ah','इ'=>'i','ई'=>'ih','उ'=>'u','ऊ'=>'uh','ऋ'=>'ry',
  'ॠ'=>'ryh','ऌ'=>'ly','ॡ'=>'lyh','ए'=>'e','ऐ'=>'ay','ओ'=>'o','औ'=>'aw',
  'अं'=>'amh','अः'=>'aq','क'=>'k','ख'=>'kh','ग'=>'g','घ'=>'gh','ङ'=>'nh',
  'च'=>'c','छ'=>'ch','ज'=>'j','झ'=>'jh','ञ'=>'ny','ट'=>'tq','ठ'=>'tqh',
  'ड'=>'dq','ढ'=>'dqh','ण'=>'nq','त'=>'t','थ'=>'th','द'=>'d','ध'=>'dh',
  'न'=>'n','प'=>'p','फ'=>'ph','ब'=>'b','भ'=>'bh','म'=>'m','य'=>'z','र'=>'r',
  'ल'=>'l','व'=>'v','श'=>'sh','ष'=>'sqh','स'=>'s','ह'=>'x',
  //Hebrew
  'א'=>'a', 'ב'=>'b','ג'=>'g','ד'=>'d','ה'=>'h','ו'=>'v','ז'=>'z','ח'=>'kh','ט'=>'th',
  'י'=>'y','ך'=>'h','כ'=>'k','ל'=>'l','ם'=>'m','מ'=>'m','ן'=>'n','נ'=>'n',
  'ס'=>'s','ע'=>'ah','ף'=>'f','פ'=>'p','ץ'=>'c','צ'=>'c','ק'=>'q','ר'=>'r',
  'ש'=>'sh','ת'=>'t',
  //Arabic
  'ا'=>'a','ب'=>'b','ت'=>'t','ث'=>'th','ج'=>'g','ح'=>'xh','خ'=>'x','د'=>'d',
  'ذ'=>'dh','ر'=>'r','ز'=>'z','س'=>'s','ش'=>'sh','ص'=>'s\'','ض'=>'d\'',
  'ط'=>'t\'','ظ'=>'z\'','ع'=>'y','غ'=>'gh','ف'=>'f','ق'=>'q','ك'=>'k',
  'ل'=>'l','م'=>'m','ن'=>'n','ه'=>'x\'','و'=>'u','ي'=>'i',

  // Japanese characters  (last update: 2008-05-09)

  // Japanese hiragana

  // 3 character syllables, っ doubles the consonant after
  'っちゃ'=>'ccha','っちぇ'=>'cche','っちょ'=>'ccho','っちゅ'=>'cchu',
  'っびゃ'=>'bbya','っびぇ'=>'bbye','っびぃ'=>'bbyi','っびょ'=>'bbyo','っびゅ'=>'bbyu',
  'っぴゃ'=>'ppya','っぴぇ'=>'ppye','っぴぃ'=>'ppyi','っぴょ'=>'ppyo','っぴゅ'=>'ppyu',
  'っちゃ'=>'ccha','っちぇ'=>'cche','っち'=>'cchi','っちょ'=>'ccho','っちゅ'=>'cchu',
  // 'っひゃ'=>'hya','っひぇ'=>'hye','っひぃ'=>'hyi','っひょ'=>'hyo','っひゅ'=>'hyu',
  'っきゃ'=>'kkya','っきぇ'=>'kkye','っきぃ'=>'kkyi','っきょ'=>'kkyo','っきゅ'=>'kkyu',
  'っぎゃ'=>'ggya','っぎぇ'=>'ggye','っぎぃ'=>'ggyi','っぎょ'=>'ggyo','っぎゅ'=>'ggyu',
  'っみゃ'=>'mmya','っみぇ'=>'mmye','っみぃ'=>'mmyi','っみょ'=>'mmyo','っみゅ'=>'mmyu',
  'っにゃ'=>'nnya','っにぇ'=>'nnye','っにぃ'=>'nnyi','っにょ'=>'nnyo','っにゅ'=>'nnyu',
  'っりゃ'=>'rrya','っりぇ'=>'rrye','っりぃ'=>'rryi','っりょ'=>'rryo','っりゅ'=>'rryu',
  'っしゃ'=>'ssha','っしぇ'=>'sshe','っし'=>'sshi','っしょ'=>'ssho','っしゅ'=>'sshu',

  // seperate hiragana 'n' ('n' + 'i' != 'ni', normally we would write "kon'nichi wa" but the apostrophe would be converted to _ anyway)
  'んあ'=>'n_a','んえ'=>'n_e','んい'=>'n_i','んお'=>'n_o','んう'=>'n_u',
  'んや'=>'n_ya','んよ'=>'n_yo','んゆ'=>'n_yu',

   // 2 character syllables - normal
  'ふぁ'=>'fa','ふぇ'=>'fe','ふぃ'=>'fi','ふぉ'=>'fo',
  'ちゃ'=>'cha','ちぇ'=>'che','ち'=>'chi','ちょ'=>'cho','ちゅ'=>'chu',
  'ひゃ'=>'hya','ひぇ'=>'hye','ひぃ'=>'hyi','ひょ'=>'hyo','ひゅ'=>'hyu',
  'びゃ'=>'bya','びぇ'=>'bye','びぃ'=>'byi','びょ'=>'byo','びゅ'=>'byu',
  'ぴゃ'=>'pya','ぴぇ'=>'pye','ぴぃ'=>'pyi','ぴょ'=>'pyo','ぴゅ'=>'pyu',
  'きゃ'=>'kya','きぇ'=>'kye','きぃ'=>'kyi','きょ'=>'kyo','きゅ'=>'kyu',
  'ぎゃ'=>'gya','ぎぇ'=>'gye','ぎぃ'=>'gyi','ぎょ'=>'gyo','ぎゅ'=>'gyu',
  'みゃ'=>'mya','みぇ'=>'mye','みぃ'=>'myi','みょ'=>'myo','みゅ'=>'myu',
  'にゃ'=>'nya','にぇ'=>'nye','にぃ'=>'nyi','にょ'=>'nyo','にゅ'=>'nyu',
  'りゃ'=>'rya','りぇ'=>'rye','りぃ'=>'ryi','りょ'=>'ryo','りゅ'=>'ryu',
  'しゃ'=>'sha','しぇ'=>'she','し'=>'shi','しょ'=>'sho','しゅ'=>'shu',
  'じゃ'=>'ja','じぇ'=>'je','じょ'=>'jo','じゅ'=>'ju',
  'うぇ'=>'we','うぃ'=>'wi',
  'いぇ'=>'ye',

  // 2 character syllables, っ doubles the consonant after
  'っば'=>'bba','っべ'=>'bbe','っび'=>'bbi','っぼ'=>'bbo','っぶ'=>'bbu',
  'っぱ'=>'ppa','っぺ'=>'ppe','っぴ'=>'ppi','っぽ'=>'ppo','っぷ'=>'ppu',
  'った'=>'tta','って'=>'tte','っち'=>'cchi','っと'=>'tto','っつ'=>'ttsu',
  'っだ'=>'dda','っで'=>'dde','っぢ'=>'ddi','っど'=>'ddo','っづ'=>'ddu',
  'っが'=>'gga','っげ'=>'gge','っぎ'=>'ggi','っご'=>'ggo','っぐ'=>'ggu',
  'っか'=>'kka','っけ'=>'kke','っき'=>'kki','っこ'=>'kko','っく'=>'kku',
  'っま'=>'mma','っめ'=>'mme','っみ'=>'mmi','っも'=>'mmo','っむ'=>'mmu',
  'っな'=>'nna','っね'=>'nne','っに'=>'nni','っの'=>'nno','っぬ'=>'nnu',
  'っら'=>'rra','っれ'=>'rre','っり'=>'rri','っろ'=>'rro','っる'=>'rru',
  'っさ'=>'ssa','っせ'=>'sse','っし'=>'sshi','っそ'=>'sso','っす'=>'ssu',
  'っざ'=>'zza','っぜ'=>'zze','っじ'=>'jji','っぞ'=>'zzo','っず'=>'zzu',

  // 1 character syllabels
  'あ'=>'a','え'=>'e','い'=>'i','お'=>'o','う'=>'u','ん'=>'n',
  'は'=>'ha','へ'=>'he','ひ'=>'hi','ほ'=>'ho','ふ'=>'fu',
  'ば'=>'ba','べ'=>'be','び'=>'bi','ぼ'=>'bo','ぶ'=>'bu',
  'ぱ'=>'pa','ぺ'=>'pe','ぴ'=>'pi','ぽ'=>'po','ぷ'=>'pu',
  'た'=>'ta','て'=>'te','ち'=>'chi','と'=>'to','つ'=>'tsu',
  'だ'=>'da','で'=>'de','ぢ'=>'di','ど'=>'do','づ'=>'du',
  'が'=>'ga','げ'=>'ge','ぎ'=>'gi','ご'=>'go','ぐ'=>'gu',
  'か'=>'ka','け'=>'ke','き'=>'ki','こ'=>'ko','く'=>'ku',
  'ま'=>'ma','め'=>'me','み'=>'mi','も'=>'mo','む'=>'mu',
  'な'=>'na','ね'=>'ne','に'=>'ni','の'=>'no','ぬ'=>'nu',
  'ら'=>'ra','れ'=>'re','り'=>'ri','ろ'=>'ro','る'=>'ru',
  'さ'=>'sa','せ'=>'se','し'=>'shi','そ'=>'so','す'=>'su',
  'わ'=>'wa','を'=>'wo',
  'ざ'=>'za','ぜ'=>'ze','じ'=>'ji','ぞ'=>'zo','ず'=>'zu',
  'や'=>'ya','よ'=>'yo','ゆ'=>'yu',
  // old characters
  'ゑ'=>'we','ゐ'=>'wi',

  //  convert what's left (probably only kicks in when something's missing above)
  // 'ぁ'=>'a','ぇ'=>'e','ぃ'=>'i','ぉ'=>'o','ぅ'=>'u',
  // 'ゃ'=>'ya','ょ'=>'yo','ゅ'=>'yu',

  // never seen one of those (disabled for the moment)
  // 'ヴぁ'=>'va','ヴぇ'=>'ve','ヴぃ'=>'vi','ヴぉ'=>'vo','ヴ'=>'vu',
  // 'でゃ'=>'dha','でぇ'=>'dhe','でぃ'=>'dhi','でょ'=>'dho','でゅ'=>'dhu',
  // 'どぁ'=>'dwa','どぇ'=>'dwe','どぃ'=>'dwi','どぉ'=>'dwo','どぅ'=>'dwu',
  // 'ぢゃ'=>'dya','ぢぇ'=>'dye','ぢぃ'=>'dyi','ぢょ'=>'dyo','ぢゅ'=>'dyu',
  // 'ふぁ'=>'fwa','ふぇ'=>'fwe','ふぃ'=>'fwi','ふぉ'=>'fwo','ふぅ'=>'fwu',
  // 'ふゃ'=>'fya','ふぇ'=>'fye','ふぃ'=>'fyi','ふょ'=>'fyo','ふゅ'=>'fyu',
  // 'すぁ'=>'swa','すぇ'=>'swe','すぃ'=>'swi','すぉ'=>'swo','すぅ'=>'swu',
  // 'てゃ'=>'tha','てぇ'=>'the','てぃ'=>'thi','てょ'=>'tho','てゅ'=>'thu',
  // 'つゃ'=>'tsa','つぇ'=>'tse','つぃ'=>'tsi','つょ'=>'tso','つ'=>'tsu',
  // 'とぁ'=>'twa','とぇ'=>'twe','とぃ'=>'twi','とぉ'=>'two','とぅ'=>'twu',
  // 'ヴゃ'=>'vya','ヴぇ'=>'vye','ヴぃ'=>'vyi','ヴょ'=>'vyo','ヴゅ'=>'vyu',
  // 'うぁ'=>'wha','うぇ'=>'whe','うぃ'=>'whi','うぉ'=>'who','うぅ'=>'whu',
  // 'じゃ'=>'zha','じぇ'=>'zhe','じぃ'=>'zhi','じょ'=>'zho','じゅ'=>'zhu',
  // 'じゃ'=>'zya','じぇ'=>'zye','じぃ'=>'zyi','じょ'=>'zyo','じゅ'=>'zyu',

  // 'spare' characters from other romanization systems
  // 'だ'=>'da','で'=>'de','ぢ'=>'di','ど'=>'do','づ'=>'du',
  // 'ら'=>'la','れ'=>'le','り'=>'li','ろ'=>'lo','る'=>'lu',
  // 'さ'=>'sa','せ'=>'se','し'=>'si','そ'=>'so','す'=>'su',
  // 'ちゃ'=>'cya','ちぇ'=>'cye','ちぃ'=>'cyi','ちょ'=>'cyo','ちゅ'=>'cyu',
  //'じゃ'=>'jya','じぇ'=>'jye','じぃ'=>'jyi','じょ'=>'jyo','じゅ'=>'jyu',
  //'りゃ'=>'lya','りぇ'=>'lye','りぃ'=>'lyi','りょ'=>'lyo','りゅ'=>'lyu',
  //'しゃ'=>'sya','しぇ'=>'sye','しぃ'=>'syi','しょ'=>'syo','しゅ'=>'syu',
  //'ちゃ'=>'tya','ちぇ'=>'tye','ちぃ'=>'tyi','ちょ'=>'tyo','ちゅ'=>'tyu',
  //'し'=>'ci',,い'=>'yi','ぢ'=>'dzi',
  //'っじゃ'=>'jja','っじぇ'=>'jje','っじ'=>'jji','っじょ'=>'jjo','っじゅ'=>'jju',


  // Japanese katakana

  // 4 character syllables: ッ doubles the consonant after, ー doubles the vowel before (usualy written with macron, but we don't want that in our URLs)
  'ッビャー'=>'bbyaa','ッビェー'=>'bbyee','ッビィー'=>'bbyii','ッビョー'=>'bbyoo','ッビュー'=>'bbyuu',
  'ッピャー'=>'ppyaa','ッピェー'=>'ppyee','ッピィー'=>'ppyii','ッピョー'=>'ppyoo','ッピュー'=>'ppyuu',
  'ッキャー'=>'kkyaa','ッキェー'=>'kkyee','ッキィー'=>'kkyii','ッキョー'=>'kkyoo','ッキュー'=>'kkyuu',
  'ッギャー'=>'ggyaa','ッギェー'=>'ggyee','ッギィー'=>'ggyii','ッギョー'=>'ggyoo','ッギュー'=>'ggyuu',
  'ッミャー'=>'mmyaa','ッミェー'=>'mmyee','ッミィー'=>'mmyii','ッミョー'=>'mmyoo','ッミュー'=>'mmyuu',
  'ッニャー'=>'nnyaa','ッニェー'=>'nnyee','ッニィー'=>'nnyii','ッニョー'=>'nnyoo','ッニュー'=>'nnyuu',
  'ッリャー'=>'rryaa','ッリェー'=>'rryee','ッリィー'=>'rryii','ッリョー'=>'rryoo','ッリュー'=>'rryuu',
  'ッシャー'=>'sshaa','ッシェー'=>'sshee','ッシー'=>'sshii','ッショー'=>'sshoo','ッシュー'=>'sshuu',
  'ッチャー'=>'cchaa','ッチェー'=>'cchee','ッチー'=>'cchii','ッチョー'=>'cchoo','ッチュー'=>'cchuu',
  'ッティー'=>'ttii',
  'ッヂィー'=>'ddii',

  // 3 character syllables - doubled vowels
  'ファー'=>'faa','フェー'=>'fee','フィー'=>'fii','フォー'=>'foo',
  'フャー'=>'fyaa','フェー'=>'fyee','フィー'=>'fyii','フョー'=>'fyoo','フュー'=>'fyuu',
  'ヒャー'=>'hyaa','ヒェー'=>'hyee','ヒィー'=>'hyii','ヒョー'=>'hyoo','ヒュー'=>'hyuu',
  'ビャー'=>'byaa','ビェー'=>'byee','ビィー'=>'byii','ビョー'=>'byoo','ビュー'=>'byuu',
  'ピャー'=>'pyaa','ピェー'=>'pyee','ピィー'=>'pyii','ピョー'=>'pyoo','ピュー'=>'pyuu',
  'キャー'=>'kyaa','キェー'=>'kyee','キィー'=>'kyii','キョー'=>'kyoo','キュー'=>'kyuu',
  'ギャー'=>'gyaa','ギェー'=>'gyee','ギィー'=>'gyii','ギョー'=>'gyoo','ギュー'=>'gyuu',
  'ミャー'=>'myaa','ミェー'=>'myee','ミィー'=>'myii','ミョー'=>'myoo','ミュー'=>'myuu',
  'ニャー'=>'nyaa','ニェー'=>'nyee','ニィー'=>'nyii','ニョー'=>'nyoo','ニュー'=>'nyuu',
  'リャー'=>'ryaa','リェー'=>'ryee','リィー'=>'ryii','リョー'=>'ryoo','リュー'=>'ryuu',
  'シャー'=>'shaa','シェー'=>'shee','シー'=>'shii','ショー'=>'shoo','シュー'=>'shuu',
  'ジャー'=>'jaa','ジェー'=>'jee','ジー'=>'jii','ジョー'=>'joo','ジュー'=>'juu',
  'スァー'=>'swaa','スェー'=>'swee','スィー'=>'swii','スォー'=>'swoo','スゥー'=>'swuu',
  'デァー'=>'daa','デェー'=>'dee','ディー'=>'dii','デォー'=>'doo','デゥー'=>'duu',
  'チャー'=>'chaa','チェー'=>'chee','チー'=>'chii','チョー'=>'choo','チュー'=>'chuu',
  'ヂャー'=>'dyaa','ヂェー'=>'dyee','ヂィー'=>'dyii','ヂョー'=>'dyoo','ヂュー'=>'dyuu',
  'ツャー'=>'tsaa','ツェー'=>'tsee','ツィー'=>'tsii','ツョー'=>'tsoo','ツー'=>'tsuu',
  'トァー'=>'twaa','トェー'=>'twee','トィー'=>'twii','トォー'=>'twoo','トゥー'=>'twuu',
  'ドァー'=>'dwaa','ドェー'=>'dwee','ドィー'=>'dwii','ドォー'=>'dwoo','ドゥー'=>'dwuu',
  'ウァー'=>'whaa','ウェー'=>'whee','ウィー'=>'whii','ウォー'=>'whoo','ウゥー'=>'whuu',
  'ヴャー'=>'vyaa','ヴェー'=>'vyee','ヴィー'=>'vyii','ヴョー'=>'vyoo','ヴュー'=>'vyuu',
  'ヴァー'=>'vaa','ヴェー'=>'vee','ヴィー'=>'vii','ヴォー'=>'voo','ヴー'=>'vuu',
  'ウェー'=>'wee','ウィー'=>'wii',
  'イェー'=>'yee',
  'ティー'=>'tii',
  'ヂィー'=>'dii',

  // 3 character syllables - doubled consonants
  'ッビャ'=>'bbya','ッビェ'=>'bbye','ッビィ'=>'bbyi','ッビョ'=>'bbyo','ッビュ'=>'bbyu',
  'ッピャ'=>'ppya','ッピェ'=>'ppye','ッピィ'=>'ppyi','ッピョ'=>'ppyo','ッピュ'=>'ppyu',
  'ッキャ'=>'kkya','ッキェ'=>'kkye','ッキィ'=>'kkyi','ッキョ'=>'kkyo','ッキュ'=>'kkyu',
  'ッギャ'=>'ggya','ッギェ'=>'ggye','ッギィ'=>'ggyi','ッギョ'=>'ggyo','ッギュ'=>'ggyu',
  'ッミャ'=>'mmya','ッミェ'=>'mmye','ッミィ'=>'mmyi','ッミョ'=>'mmyo','ッミュ'=>'mmyu',
  'ッニャ'=>'nnya','ッニェ'=>'nnye','ッニィ'=>'nnyi','ッニョ'=>'nnyo','ッニュ'=>'nnyu',
  'ッリャ'=>'rrya','ッリェ'=>'rrye','ッリィ'=>'rryi','ッリョ'=>'rryo','ッリュ'=>'rryu',
  'ッシャ'=>'ssha','ッシェ'=>'sshe','ッシ'=>'sshi','ッショ'=>'ssho','ッシュ'=>'sshu',
  'ッチャ'=>'ccha','ッチェ'=>'cche','ッチ'=>'cchi','ッチョ'=>'ccho','ッチュ'=>'cchu',
  'ッティ'=>'tti',
  'ッヂィ'=>'ddi',

  // 3 character syllables - doubled vowel and consonants
  'ッバー'=>'bbaa','ッベー'=>'bbee','ッビー'=>'bbii','ッボー'=>'bboo','ッブー'=>'bbuu',
  'ッパー'=>'ppaa','ッペー'=>'ppee','ッピー'=>'ppii','ッポー'=>'ppoo','ップー'=>'ppuu',
  'ッケー'=>'kkee','ッキー'=>'kkii','ッコー'=>'kkoo','ックー'=>'kkuu','ッカー'=>'kkaa',
  'ッガー'=>'ggaa','ッゲー'=>'ggee','ッギー'=>'ggii','ッゴー'=>'ggoo','ッグー'=>'gguu',
  'ッマー'=>'maa','ッメー'=>'mee','ッミー'=>'mii','ッモー'=>'moo','ッムー'=>'muu',
  'ッナー'=>'nnaa','ッネー'=>'nnee','ッニー'=>'nnii','ッノー'=>'nnoo','ッヌー'=>'nnuu',
  'ッラー'=>'rraa','ッレー'=>'rree','ッリー'=>'rrii','ッロー'=>'rroo','ッルー'=>'rruu',
  'ッサー'=>'ssaa','ッセー'=>'ssee','ッシー'=>'sshii','ッソー'=>'ssoo','ッスー'=>'ssuu',
  'ッザー'=>'zzaa','ッゼー'=>'zzee','ッジー'=>'jjii','ッゾー'=>'zzoo','ッズー'=>'zzuu',
  'ッター'=>'ttaa','ッテー'=>'ttee','ッチー'=>'chii','ットー'=>'ttoo','ッツー'=>'ttsuu',
  'ッダー'=>'ddaa','ッデー'=>'ddee','ッヂー'=>'ddii','ッドー'=>'ddoo','ッヅー'=>'dduu',

  // 2 character syllables - normal
  'ファ'=>'fa','フェ'=>'fe','フィ'=>'fi','フォ'=>'fo','フゥ'=>'fu',
  // 'フャ'=>'fya','フェ'=>'fye','フィ'=>'fyi','フョ'=>'fyo','フュ'=>'fyu',
  'フャ'=>'fa','フェ'=>'fe','フィ'=>'fi','フョ'=>'fo','フュ'=>'fu',
  'ヒャ'=>'hya','ヒェ'=>'hye','ヒィ'=>'hyi','ヒョ'=>'hyo','ヒュ'=>'hyu',
  'ビャ'=>'bya','ビェ'=>'bye','ビィ'=>'byi','ビョ'=>'byo','ビュ'=>'byu',
  'ピャ'=>'pya','ピェ'=>'pye','ピィ'=>'pyi','ピョ'=>'pyo','ピュ'=>'pyu',
  'キャ'=>'kya','キェ'=>'kye','キィ'=>'kyi','キョ'=>'kyo','キュ'=>'kyu',
  'ギャ'=>'gya','ギェ'=>'gye','ギィ'=>'gyi','ギョ'=>'gyo','ギュ'=>'gyu',
  'ミャ'=>'mya','ミェ'=>'mye','ミィ'=>'myi','ミョ'=>'myo','ミュ'=>'myu',
  'ニャ'=>'nya','ニェ'=>'nye','ニィ'=>'nyi','ニョ'=>'nyo','ニュ'=>'nyu',
  'リャ'=>'rya','リェ'=>'rye','リィ'=>'ryi','リョ'=>'ryo','リュ'=>'ryu',
  'シャ'=>'sha','シェ'=>'she','ショ'=>'sho','シュ'=>'shu',
  'ジャ'=>'ja','ジェ'=>'je','ジョ'=>'jo','ジュ'=>'ju',
  'スァ'=>'swa','スェ'=>'swe','スィ'=>'swi','スォ'=>'swo','スゥ'=>'swu',
  'デァ'=>'da','デェ'=>'de','ディ'=>'di','デォ'=>'do','デゥ'=>'du',
  'チャ'=>'cha','チェ'=>'che','チ'=>'chi','チョ'=>'cho','チュ'=>'chu',
  // 'ヂャ'=>'dya','ヂェ'=>'dye','ヂィ'=>'dyi','ヂョ'=>'dyo','ヂュ'=>'dyu',
  'ツャ'=>'tsa','ツェ'=>'tse','ツィ'=>'tsi','ツョ'=>'tso','ツ'=>'tsu',
  'トァ'=>'twa','トェ'=>'twe','トィ'=>'twi','トォ'=>'two','トゥ'=>'twu',
  'ドァ'=>'dwa','ドェ'=>'dwe','ドィ'=>'dwi','ドォ'=>'dwo','ドゥ'=>'dwu',
  'ウァ'=>'wha','ウェ'=>'whe','ウィ'=>'whi','ウォ'=>'who','ウゥ'=>'whu',
  'ヴャ'=>'vya','ヴェ'=>'vye','ヴィ'=>'vyi','ヴョ'=>'vyo','ヴュ'=>'vyu',
  'ヴァ'=>'va','ヴェ'=>'ve','ヴィ'=>'vi','ヴォ'=>'vo','ヴ'=>'vu',
  'ウェ'=>'we','ウィ'=>'wi',
  'イェ'=>'ye',
  'ティ'=>'ti',
  'ヂィ'=>'di',

  // 2 character syllables - doubled vocal
  'アー'=>'aa','エー'=>'ee','イー'=>'ii','オー'=>'oo','ウー'=>'uu',
  'ダー'=>'daa','デー'=>'dee','ヂー'=>'dii','ドー'=>'doo','ヅー'=>'duu',
  'ハー'=>'haa','ヘー'=>'hee','ヒー'=>'hii','ホー'=>'hoo','フー'=>'fuu',
  'バー'=>'baa','ベー'=>'bee','ビー'=>'bii','ボー'=>'boo','ブー'=>'buu',
  'パー'=>'paa','ペー'=>'pee','ピー'=>'pii','ポー'=>'poo','プー'=>'puu',
  'ケー'=>'kee','キー'=>'kii','コー'=>'koo','クー'=>'kuu','カー'=>'kaa',
  'ガー'=>'gaa','ゲー'=>'gee','ギー'=>'gii','ゴー'=>'goo','グー'=>'guu',
  'マー'=>'maa','メー'=>'mee','ミー'=>'mii','モー'=>'moo','ムー'=>'muu',
  'ナー'=>'naa','ネー'=>'nee','ニー'=>'nii','ノー'=>'noo','ヌー'=>'nuu',
  'ラー'=>'raa','レー'=>'ree','リー'=>'rii','ロー'=>'roo','ルー'=>'ruu',
  'サー'=>'saa','セー'=>'see','シー'=>'shii','ソー'=>'soo','スー'=>'suu',
  'ザー'=>'zaa','ゼー'=>'zee','ジー'=>'jii','ゾー'=>'zoo','ズー'=>'zuu',
  'ター'=>'taa','テー'=>'tee','チー'=>'chii','トー'=>'too','ツー'=>'tsuu',
  'ワー'=>'waa','ヲー'=>'woo',
  'ヤー'=>'yaa','ヨー'=>'yoo','ユー'=>'yuu',
  'ヵー'=>'kaa','ヶー'=>'kee',
  // old characters
  'ヱー'=>'wee','ヰー'=>'wii',

  // seperate katakana 'n'
  'ンア'=>'n_a','ンエ'=>'n_e','ンイ'=>'n_i','ンオ'=>'n_o','ンウ'=>'n_u',
  'ンヤ'=>'n_ya','ンヨ'=>'n_yo','ンユ'=>'n_yu',

  // 2 character syllables - doubled consonants
  'ッバ'=>'bba','ッベ'=>'bbe','ッビ'=>'bbi','ッボ'=>'bbo','ッブ'=>'bbu',
  'ッパ'=>'ppa','ッペ'=>'ppe','ッピ'=>'ppi','ッポ'=>'ppo','ップ'=>'ppu',
  'ッケ'=>'kke','ッキ'=>'kki','ッコ'=>'kko','ック'=>'kku','ッカ'=>'kka',
  'ッガ'=>'gga','ッゲ'=>'gge','ッギ'=>'ggi','ッゴ'=>'ggo','ッグ'=>'ggu',
  'ッマ'=>'ma','ッメ'=>'me','ッミ'=>'mi','ッモ'=>'mo','ッム'=>'mu',
  'ッナ'=>'nna','ッネ'=>'nne','ッニ'=>'nni','ッノ'=>'nno','ッヌ'=>'nnu',
  'ッラ'=>'rra','ッレ'=>'rre','ッリ'=>'rri','ッロ'=>'rro','ッル'=>'rru',
  'ッサ'=>'ssa','ッセ'=>'sse','ッシ'=>'sshi','ッソ'=>'sso','ッス'=>'ssu',
  'ッザ'=>'zza','ッゼ'=>'zze','ッジ'=>'jji','ッゾ'=>'zzo','ッズ'=>'zzu',
  'ッタ'=>'tta','ッテ'=>'tte','ッチ'=>'cchi','ット'=>'tto','ッツ'=>'ttsu',
  'ッダ'=>'dda','ッデ'=>'dde','ッヂ'=>'ddi','ッド'=>'ddo','ッヅ'=>'ddu',

  // 1 character syllables
  'ア'=>'a','エ'=>'e','イ'=>'i','オ'=>'o','ウ'=>'u','ン'=>'n',
  'ハ'=>'ha','ヘ'=>'he','ヒ'=>'hi','ホ'=>'ho','フ'=>'fu',
  'バ'=>'ba','ベ'=>'be','ビ'=>'bi','ボ'=>'bo','ブ'=>'bu',
  'パ'=>'pa','ペ'=>'pe','ピ'=>'pi','ポ'=>'po','プ'=>'pu',
  'ケ'=>'ke','キ'=>'ki','コ'=>'ko','ク'=>'ku','カ'=>'ka',
  'ガ'=>'ga','ゲ'=>'ge','ギ'=>'gi','ゴ'=>'go','グ'=>'gu',
  'マ'=>'ma','メ'=>'me','ミ'=>'mi','モ'=>'mo','ム'=>'mu',
  'ナ'=>'na','ネ'=>'ne','ニ'=>'ni','ノ'=>'no','ヌ'=>'nu',
  'ラ'=>'ra','レ'=>'re','リ'=>'ri','ロ'=>'ro','ル'=>'ru',
  'サ'=>'sa','セ'=>'se','シ'=>'shi','ソ'=>'so','ス'=>'su',
  'ザ'=>'za','ゼ'=>'ze','ジ'=>'ji','ゾ'=>'zo','ズ'=>'zu',
  'タ'=>'ta','テ'=>'te','チ'=>'chi','ト'=>'to','ツ'=>'tsu',
  'ダ'=>'da','デ'=>'de','ヂ'=>'di','ド'=>'do','ヅ'=>'du',
  'ワ'=>'wa','ヲ'=>'wo',
  'ヤ'=>'ya','ヨ'=>'yo','ユ'=>'yu',
  'ヵ'=>'ka','ヶ'=>'ke',
  // old characters
  'ヱ'=>'we','ヰ'=>'wi',

  //  convert what's left (probably only kicks in when something's missing above)
  'ァ'=>'a','ェ'=>'e','ィ'=>'i','ォ'=>'o','ゥ'=>'u',
  'ャ'=>'ya','ョ'=>'yo','ュ'=>'yu',

  // special characters
  '・'=>'_','、'=>'_',
  'ー'=>'_', // when used with hiragana (seldom), this character would not be converted otherwise

  // 'ラ'=>'la','レ'=>'le','リ'=>'li','ロ'=>'lo','ル'=>'lu',
  // 'チャ'=>'cya','チェ'=>'cye','チィ'=>'cyi','チョ'=>'cyo','チュ'=>'cyu',
  //'デャ'=>'dha','デェ'=>'dhe','ディ'=>'dhi','デョ'=>'dho','デュ'=>'dhu',
  // 'リャ'=>'lya','リェ'=>'lye','リィ'=>'lyi','リョ'=>'lyo','リュ'=>'lyu',
  // 'テャ'=>'tha','テェ'=>'the','ティ'=>'thi','テョ'=>'tho','テュ'=>'thu',
  //'ファ'=>'fwa','フェ'=>'fwe','フィ'=>'fwi','フォ'=>'fwo','フゥ'=>'fwu',
  //'チャ'=>'tya','チェ'=>'tye','チィ'=>'tyi','チョ'=>'tyo','チュ'=>'tyu',
  // 'ジャ'=>'jya','ジェ'=>'jye','ジィ'=>'jyi','ジョ'=>'jyo','ジュ'=>'jyu',
  // 'ジャ'=>'zha','ジェ'=>'zhe','ジィ'=>'zhi','ジョ'=>'zho','ジュ'=>'zhu',
  //'ジャ'=>'zya','ジェ'=>'zye','ジィ'=>'zyi','ジョ'=>'zyo','ジュ'=>'zyu',
  //'シャ'=>'sya','シェ'=>'sye','シィ'=>'syi','ショ'=>'syo','シュ'=>'syu',
  //'シ'=>'ci','フ'=>'hu',シ'=>'si','チ'=>'ti','ツ'=>'tu','イ'=>'yi','ヂ'=>'dzi',

  // "Greeklish"
  'Γ'=>'G','Δ'=>'E','Θ'=>'Th','Λ'=>'L','Ξ'=>'X','Π'=>'P','Σ'=>'S','Φ'=>'F','Ψ'=>'Ps',
  'γ'=>'g','δ'=>'e','θ'=>'th','λ'=>'l','ξ'=>'x','π'=>'p','σ'=>'s','φ'=>'f','ψ'=>'ps',

  // Thai
  'ก'=>'k','ข'=>'kh','ฃ'=>'kh','ค'=>'kh','ฅ'=>'kh','ฆ'=>'kh','ง'=>'ng','จ'=>'ch',
  'ฉ'=>'ch','ช'=>'ch','ซ'=>'s','ฌ'=>'ch','ญ'=>'y','ฎ'=>'d','ฏ'=>'t','ฐ'=>'th',
  'ฑ'=>'d','ฒ'=>'th','ณ'=>'n','ด'=>'d','ต'=>'t','ถ'=>'th','ท'=>'th','ธ'=>'th',
  'น'=>'n','บ'=>'b','ป'=>'p','ผ'=>'ph','ฝ'=>'f','พ'=>'ph','ฟ'=>'f','ภ'=>'ph',
  'ม'=>'m','ย'=>'y','ร'=>'r','ฤ'=>'rue','ฤๅ'=>'rue','ล'=>'l','ฦ'=>'lue',
  'ฦๅ'=>'lue','ว'=>'w','ศ'=>'s','ษ'=>'s','ส'=>'s','ห'=>'h','ฬ'=>'l','ฮ'=>'h',
  'ะ'=>'a','ั'=>'a','รร'=>'a','า'=>'a','ๅ'=>'a','ำ'=>'am','ํา'=>'am',
  'ิ'=>'i','ี'=>'i','ึ'=>'ue','ี'=>'ue','ุ'=>'u','ู'=>'u',
  'เ'=>'e','แ'=>'ae','โ'=>'o','อ'=>'o',
  'ียะ'=>'ia','ีย'=>'ia','ือะ'=>'uea','ือ'=>'uea','ัวะ'=>'ua','ัว'=>'ua',
  'ใ'=>'ai','ไ'=>'ai','ัย'=>'ai','าย'=>'ai','าว'=>'ao',
  'ุย'=>'ui','อย'=>'oi','ือย'=>'ueai','วย'=>'uai',
  'ิว'=>'io','็ว'=>'eo','ียว'=>'iao',
  '่'=>'','้'=>'','๊'=>'','๋'=>'','็'=>'',
  '์'=>'','๎'=>'','ํ'=>'','ฺ'=>'',
  'ๆ'=>'2','๏'=>'o','ฯ'=>'-','๚'=>'-','๛'=>'-',
  '๐'=>'0','๑'=>'1','๒'=>'2','๓'=>'3','๔'=>'4',
  '๕'=>'5','๖'=>'6','๗'=>'7','๘'=>'8','๙'=>'9',

  // Korean
  'ㄱ'=>'k','ㅋ'=>'kh','ㄲ'=>'kk','ㄷ'=>'t','ㅌ'=>'th','ㄸ'=>'tt','ㅂ'=>'p',
  'ㅍ'=>'ph','ㅃ'=>'pp','ㅈ'=>'c','ㅊ'=>'ch','ㅉ'=>'cc','ㅅ'=>'s','ㅆ'=>'ss',
  'ㅎ'=>'h','ㅇ'=>'ng','ㄴ'=>'n','ㄹ'=>'l','ㅁ'=>'m', 'ㅏ'=>'a','ㅓ'=>'e','ㅗ'=>'o',
  'ㅜ'=>'wu','ㅡ'=>'u','ㅣ'=>'i','ㅐ'=>'ay','ㅔ'=>'ey','ㅚ'=>'oy','ㅘ'=>'wa','ㅝ'=>'we',
  'ㅟ'=>'wi','ㅙ'=>'way','ㅞ'=>'wey','ㅢ'=>'uy','ㅑ'=>'ya','ㅕ'=>'ye','ㅛ'=>'oy',
  'ㅠ'=>'yu','ㅒ'=>'yay','ㅖ'=>'yey',
);