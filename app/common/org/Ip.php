<?php 
namespace org;

use Exception;

class Ip
{

    private static $ip = null;

    private static $fp = null;

    private static $offset = null;

    private static $index = null;

    private static $cached = array();

    public static function find($ip)
    {
        if (empty( $ip ) === true) {
            $ip = self::getIp();
        }

        $nip   = gethostbyname($ip);
        $ipdot = explode('.', $nip);

        if ($ipdot[0] < 0 || $ipdot[0] > 255 || count($ipdot) !== 4) {
            return ['N/A'];
        }

        if (isset( self::$cached[$nip] ) === true) {
            return self::$cached[$nip];
        }

        if (self::$fp === null) {
            self::init();
        }

        $nip2 = pack('N', ip2long($nip));

        $tmp_offset = (int) $ipdot[0] * 4;
        $start      = unpack('Vlen',
            self::$index[$tmp_offset].self::$index[$tmp_offset + 1].self::$index[$tmp_offset + 2].self::$index[$tmp_offset + 3]);

        $index_offset = $index_length = null;
        $max_comp_len = self::$offset['len'] - 1024 - 4;
        for ($start = $start['len'] * 8 + 1024; $start < $max_comp_len; $start += 8) {
            if (self::$index{$start}.self::$index{$start + 1}.self::$index{$start + 2}.self::$index{$start + 3} >= $nip2) {
                $index_offset = unpack('Vlen',
                    self::$index{$start + 4}.self::$index{$start + 5}.self::$index{$start + 6}."\x0");
                $index_length = unpack('Clen', self::$index{$start + 7});

                break;
            }
        }

        if ($index_offset === null) {
            return ['N/A'];
        }

        fseek(self::$fp, self::$offset['len'] + $index_offset['len'] - 1024);

        self::$cached[$nip] = explode("\t", fread(self::$fp, $index_length['len']));

        array_unshift(self::$cached[$nip], self::countryToCode(self::$cached[$nip]));

        return self::$cached[$nip];
    }

    private static function init()
    {
        if (self::$fp === null) {
            self::$ip = new self();

            self::$fp = fopen(__DIR__.'/ip/17monipdb.dat', 'rb');
            if (self::$fp === false) {
                throw new Exception('Invalid 17monipdb.dat file!');
            }

            self::$offset = unpack('Nlen', fread(self::$fp, 4));
            if (self::$offset['len'] < 4) {
                throw new Exception('Invalid 17monipdb.dat file!');
            }

            self::$index = fread(self::$fp, self::$offset['len'] - 4);
        }
    }

    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @return mixed
     */
    static public function getIp($type = 0)
    {
        $type       = $type ? 1 : 0;
        static $ip  = null;
        if ($ip !== null) {
            return $ip[$type];
        }
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $arr    = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            $pos    = array_search('unknown', $arr);
            if(false !== $pos) {
                unset($arr[$pos]);
            }
            $ip     = trim($arr[0]);
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip     = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip     = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long       = sprintf("%u",ip2long($ip));
        $ip         = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }

    static public function countryToCode($ip=[])
    {
        $codes      = [
            '安哥拉'=>'AO',
            '阿富汗'=>'AF',
            '阿尔巴尼亚'=>'AL',
            '阿尔及利亚'=>'DZ',
            '安道尔共和国'=>'AD',
            '安圭拉岛'=>'AI',
            '安提瓜和巴布达'=>'AG',
            '阿根廷'=>'AR',
            '亚美尼亚'=>'AM',
            '澳大利亚'=>'AU',
            '奥地利'=>'AT',
            '阿塞拜疆'=>'AZ',
            '巴哈马'=>'BS',
            '巴林'=>'BH',
            '孟加拉国'=>'BD',
            '巴巴多斯'=>'BB',
            '白俄罗斯'=>'BY',
            '比利时'=>'BE',
            '伯利兹'=>'BZ',
            '贝宁'=>'BJ',
            '百慕大群岛'=>'BM',
            '玻利维亚'=>'BO',
            '博茨瓦纳'=>'BW',
            '巴西'=>'BR',
            '文莱'=>'BN',
            '保加利亚'=>'BG',
            '布基纳法索'=>'BF',
            '缅甸'=>'MM',
            '布隆迪'=>'BI',
            '喀麦隆'=>'CM',
            '加拿大'=>'CA',
            '中非共和国'=>'CF',
            '乍得'=>'TD',
            '智利'=>'CL',
            '中国'=>'CN',
            '哥伦比亚'=>'CO',
            '刚果'=>'CG',
            '库克群岛'=>'CK',
            '哥斯达黎加'=>'CR',
            '古巴'=>'CU',
            '塞浦路斯'=>'CY',
            '捷克'=>'CZ',
            '丹麦'=>'DK',
            '吉布提'=>'DJ',
            '多米尼加共和国'=>'DO',
            '厄瓜多尔'=>'EC',
            '埃及'=>'EG',
            '萨尔瓦多'=>'SV',
            '爱沙尼亚'=>'EE',
            '埃塞俄比亚'=>'ET',
            '斐济'=>'FJ',
            '芬兰'=>'FI',
            '法国'=>'FR',
            '法属圭亚那'=>'GF',
            '加蓬'=>'GA',
            '冈比亚'=>'GM',
            '格鲁吉亚'=>'GE',
            '德国'=>'DE',
            '加纳'=>'GH',
            '直布罗陀'=>'GI',
            '希腊'=>'GR',
            '格林纳达'=>'GD',
            '关岛'=>'GU',
            '危地马拉'=>'GT',
            '几内亚'=>'GN',
            '圭亚那'=>'GY',
            '海地'=>'HT',
            '洪都拉斯'=>'HN',
            '香港'=>'HK',
            '匈牙利'=>'HU',
            '冰岛'=>'IS',
            '印度'=>'IN',
            '印度尼西亚'=>'ID',
            '伊朗'=>'IR',
            '伊拉克'=>'IQ',
            '爱尔兰'=>'IE',
            '以色列'=>'IL',
            '意大利'=>'IT',
            '牙买加'=>'JM',
            '日本'=>'JP',
            '约旦'=>'JO',
            '柬埔寨'=>'KH',
            '哈萨克斯坦'=>'KZ',
            '肯尼亚'=>'KE',
            '韩国'=>'KR',
            '科威特'=>'KW',
            '吉尔吉斯坦'=>'KG',
            '老挝'=>'LA',
            '拉脱维亚'=>'LV',
            '黎巴嫩'=>'LB',
            '莱索托'=>'LS',
            '利比里亚'=>'LR',
            '利比亚'=>'LY',
            '台湾'=>'TW',
        ];

        if (isset($codes[$ip[1]])) 
            return $codes[$ip[1]];
        elseif(isset($codes[$ip[0]])) 
            return $codes[$ip[0]];
        else
            return '';
    }

    public function __destruct()
    {
        if (self::$fp !== null) {
            fclose(self::$fp);
        }
    }
}