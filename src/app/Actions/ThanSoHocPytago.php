<?php

namespace App\Actions;

use Illuminate\Support\Str;

// use Spatie\QueueableAction\QueueableAction;

class ThanSoHocPytago
{
    // use QueueableAction;

    public array $pytago;

    // input
    private int $ngay;
    private int $thang;
    private int $nam;
    private string $hoTen;

    // calculate first
    private string $ten;
    private array $tenNumber;
    private array $damMe;
    private int $sumNgay;
    private int $sumThang;
    private int $sumNam;

    private int $tongNguyenAm=0;
    private int $tongPhuAm=0;
    private int $tongChuCaiDau=0;

    private $letterToNumber = [
        'A' => 1, 'J' => 1, 'S' => 1,
        'B' => 2, 'K' => 2, 'T' => 2,
        'C' => 3, 'L' => 3, 'U' => 3,
        'D' => 4, 'M' => 4, 'V' => 4,
        'E' => 5, 'N' => 5, 'W' => 5,
        'F' => 6, 'O' => 6, 'X' => 6,
        'G' => 7, 'P' => 7, 'Y' => 7,
        'H' => 8, 'Q' => 8, 'Z' => 8,
        'I' => 9, 'R' => 9,
    ];

    private $nguyenAm = ['A', 'E', 'I', 'O', 'U'];
    private $oNumber = [1,2,3,4,5,6,7,8,9];

    /**
     * Create a new action instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Prepare the action for execution, leveraging constructor injection.
    }

    /**
     * Execute the action.
     *
     * @return mixed
     */
    public function execute(int $ngay, int $thang, int $nam, string $fullName = '')
    {
        $this->ngay = $ngay;
        $this->thang = $thang;
        $this->nam = $nam;
        $this->hoTen = Str::upper(Str::ascii($fullName));
        $this->init();
        // The business logic.
        $this->calculate();

        return $this->pytago;
    }

    // Goi dung theo thu tu
    // TODO validate data input
    private function calculate(): void
    {
        $this->getDuongDoi();
        $this->getSuMenh();
        $this->getLkDuongDoiSuMenh();
        $this->getLinhHon();
        $this->getNhanCach();
        $this->getLkLinhHonNhanCach();
        $this->getCanBang();
        $this->getNgaySinh();
        $this->getTruongThanh();
        $this->getSoThieu();
        $this->getSucManhTiemThuc();
        $this->getTuDuyLiTri();
        $this->getDamMe();
        $this->getChiSoNam();
        $this->getChiSoThang();
        $this->getChang();
        $this->getThachThuc();
    }
    private function getDuongDoi(): int
    {
        return $this->pytago['duong_doi'] = $this->sumToDigitRecursive(
            $this->sumNgay + $this->sumThang + $this->sumNam
        );
    }
    private function getSuMenh(): int
    {
        if ('' === $this->hoTen) {
            return -1;
        }
        $words = explode(' ', $this->hoTen);
        $a = [];
        $ten = '';
        $sumChuCaiDau = 0;
        collect($words)->map(function($word) use(&$a, &$ten, &$sumChuCaiDau){
            $a[] = $this->sumWordToDigit($word);
            $ten = $word;
            $sumChuCaiDau += $this->letterToNumber[$word[0]];
        });
        $this->ten = $ten;
        $this->tongChuCaiDau = $sumChuCaiDau;
        return $this->pytago['su_menh'] = $this->sumToDigitRecursive(collect($a)->sum());
    }
    private function getLkDuongDoiSuMenh(): int
    {
        return $this->pytago['lk_duongdoi_sumenh'] = abs($this->pytago['duong_doi'] - $this->pytago['su_menh']);
    }
    private function getLinhHon(): int
    {
        return $this->pytago['linh_hon'] = $this->sumToDigitRecursive($this->tongNguyenAm);
    }
    private function getNhanCach(): int
    {
        return $this->pytago['nhan_cach'] = $this->sumToDigitRecursive($this->tongPhuAm);
    }
    private function getLkLinhHonNhanCach(): int
    {
        return $this->pytago['lk_linhhon_nhancach'] = abs($this->pytago['linh_hon'] - $this->pytago['nhan_cach']);
    }
    private function getCanBang(): int
    {
        return $this->pytago['can_bang'] = $this->sumToDigitRecursive($this->tongChuCaiDau);
    }
    private function getNgaySinh(): int
    {
        return $this->pytago['ngay_sinh'] = $this->sumNgay;
    }
    private function getTruongThanh(): int
    {
        return $this->pytago['truong_thanh'] = $this->sumToDigitRecursive($this->pytago['duong_doi'] + $this->pytago['su_menh']);
    }
    private function getSoThieu(): array
    {
        return $this->pytago['so_thieu'] = array_values(array_diff($this->oNumber, $this->tenNumber));
    }
    private function getSucManhTiemThuc(): int
    {
        return $this->pytago['suc_manh_tiem_thuc'] = 9 - count($this->pytago['so_thieu']);
    }
    private function getTuDuyLiTri(): int
    {
        return $this->pytago['tu_duy_li_tri'] = $this->sumToDigitRecursive(
            $this->sumWordToDigit($this->ten)+$this->sumNgay
        );
    }
    private function getDamMe(): array
    {
        return $this->pytago['dam_me'] = $this->damMe;
    }
    private function getChiSoNam(): int
    {
        return $this->pytago['chi_so_nam'] = $this->sumToDigitRecursive(
            $this->sumNgay
            + $this->sumThang
            + $this->sumToDigitRecursive((int)date('Y'))
        );
    }
    private function getChiSoThang(): int
    {
        return $this->pytago['chi_so_thang'] = $this->sumToDigitRecursive(
            $this->pytago['chi_so_nam'] + $this->sumToDigit((int)date('m'))
        );
    }
    private function getChang(): array
    {        
        $chang1 = $this->sumToDigitRecursive($this->sumNgay + $this->sumThang);
        $chang2 = $this->sumToDigitRecursive($this->sumNgay + $this->sumNam);
        $chang3 = $this->sumToDigitRecursive($chang1 + $chang2);
        $chang4 = $this->sumToDigitRecursive($this->sumThang + $this->sumNam);
        return $this->pytago['chang'] = [
            'chang_1' => $chang1,
            'chang_2' => $chang2,
            'chang_3' => $chang3,
            'chang_4' => $chang4,
        ];
    }
    private function getThachThuc(): array
    {
        $thachThuc1 = abs($this->sumThang - $this->sumNgay);
        $thachThuc2 = abs($this->sumNgay - $this->sumNam);
        $thachThuc3 = abs($thachThuc1 - $thachThuc2);
        $thachThuc4 = abs($this->sumThang - $this->sumNam);
        return $this->pytago['thach_thuc'] = [
            'thachThuc_1' => $thachThuc1,
            'thachThuc_2' => $thachThuc2,
            'thachThuc_3' => $thachThuc3,
            'thachThuc_4' => $thachThuc4,
        ];
    }

    // Tinh_Toan
    private function init()
    {
        $this->getTongNguyenAmPhuAm();
        $this->sumNgay = $this->sumToDigitRecursive($this->ngay);
        $this->sumThang = $this->sumToDigit($this->thang);
        $this->sumNam = $this->sumToDigitRecursive($this->nam);

        return $this;
    }
    private function getTongNguyenAmPhuAm()
    {
        $tenNguyenAm = [];

        $chars = str_split(Str::remove(' ', $this->hoTen));
        $count = count($chars);
        $endcheck = $count - 1;
        for($i = 0; $i < $count; $i++)
		{
            // Truong hop dac biet
			if($chars[$i] === 'Y' && $i < $endcheck)
			{
				if((!in_array($chars[$i+1], $this->nguyenAm)) && (!in_array($chars[$i-1], $this->nguyenAm)))
                {
                    $this->tongNguyenAm += $this->letterToNumber[$chars[$i]];
                    array_push($tenNguyenAm, 'Y');
                }
			}

			if(in_array($chars[$i], $this->nguyenAm))
			{
                $this->tongNguyenAm += $this->letterToNumber[$chars[$i]];
			} else {
                $this->tongPhuAm += $this->letterToNumber[$chars[$i]];
            }
            $this->tenNumber[] = $this->letterToNumber[$chars[$i]];
            $this->damMe = collect(array_count_values($this->tenNumber))->filter(function($count){
                return $count >= 3;
            })->all();
		}
        $this->tenNumber = array_unique($this->tenNumber);
        return $this;
    }
    private function sumWordToDigit(string $word)
    {
        $chars = str_split($word);
        $sum = 0;
        collect($chars)->map(function($char) use(&$sum) {
            $sum += $this->letterToNumber[$char];
        });
        return $this->sumToDigitRecursive($sum);
    }
    private function sumToDigitRecursive(int $number): int
    {
        $sum = $this->sumToDigit($number);
        if ($sum >= 10) {
            $sum = $this->sumToDigitRecursive($sum);
        }

        return $sum;
    }
    private function sumToDigit(int $number): int
    {
        $sum = 0;
        while ($number > 0) {
            $digit = $number % 10;
			$sum += $digit;
			$number = ($number - $digit) / 10;
        }
        return $sum;
    }
}
