<?php

namespace Database\Seeders;

use App\Models\FinancialMonth;
use App\Models\FinancialYear;
use App\Models\SevenDaysFinancialMonth;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class FinancialYearSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $years = [
            ['name' => '1960/1961', 'code' => '1960'],
            ['name' => '1961/1962', 'code' => '1961'],
            ['name' => '1962/1963', 'code' => '1962'],
            ['name' => '1963/1964', 'code' => '1963'],
            ['name' => '1964/1965', 'code' => '1964'],
            ['name' => '1965/1966', 'code' => '1965'],
            ['name' => '1966/1967', 'code' => '1966'],
            ['name' => '1967/1968', 'code' => '1967'],
            ['name' => '1968/1969', 'code' => '1968'],
            ['name' => '1969/1970', 'code' => '1969'],
            ['name' => '1970/1971', 'code' => '1970'],
            ['name' => '1971/1972', 'code' => '1971'],
            ['name' => '1972/1973', 'code' => '1972'],
            ['name' => '1973/1974', 'code' => '1973'],
            ['name' => '1974/1975', 'code' => '1974'],
            ['name' => '1975/1976', 'code' => '1975'],
            ['name' => '1976/1977', 'code' => '1976'],
            ['name' => '1977/1978', 'code' => '1977'],
            ['name' => '1978/1979', 'code' => '1978'],
            ['name' => '1979/1980', 'code' => '1979'],
            ['name' => '1980/1981', 'code' => '1980'],
            ['name' => '1981/1982', 'code' => '1981'],
            ['name' => '1982/1983', 'code' => '1982'],
            ['name' => '1983/1984', 'code' => '1983'],
            ['name' => '1984/1985', 'code' => '1984'],
            ['name' => '1985/1986', 'code' => '1985'],
            ['name' => '1986/1987', 'code' => '1986'],
            ['name' => '1987/1988', 'code' => '1987'],
            ['name' => '1988/1989', 'code' => '1988'],
            ['name' => '1989/1990', 'code' => '1989'],
            ['name' => '1990/1991', 'code' => '1990'],
            ['name' => '1991/1992', 'code' => '1991'],
            ['name' => '1992/1993', 'code' => '1992'],
            ['name' => '1993/1994', 'code' => '1993'],
            ['name' => '1994/1995', 'code' => '1994'],
            ['name' => '1995/1996', 'code' => '1995'],
            ['name' => '1996/1997', 'code' => '1996'],
            ['name' => '1997/1998', 'code' => '1997'],
            ['name' => '1998/1999', 'code' => '1998'],
            ['name' => '1999/2000', 'code' => '1999'],
            ['name' => '2000/2001', 'code' => '2000'],
            ['name' => '2001/2002', 'code' => '2001'],
            ['name' => '2002/2003', 'code' => '2002'],
            ['name' => '2003/2004', 'code' => '2003'],
            ['name' => '2004/2005', 'code' => '2004'],
            ['name' => '2005/2006', 'code' => '2005'],
            ['name' => '2006/2007', 'code' => '2006'],
            ['name' => '2007/2008', 'code' => '2007'],
            ['name' => '2008/2009', 'code' => '2008'],
            ['name' => '2009/2010', 'code' => '2009'],
            ['name' => '2010/2011', 'code' => '2010'],
            ['name' => '2011/2012', 'code' => '2011'],
            ['name' => '2012/2013', 'code' => '2012'],
            ['name' => '2013/2014', 'code' => '2013'],
            ['name' => '2014/2015', 'code' => '2014'],
            ['name' => '2015/2016', 'code' => '2015'],
            ['name' => '2016/2017', 'code' => '2016'],
            ['name' => '2017/2018', 'code' => '2017'],
            ['name' => '2018/2019', 'code' => '2018'],
            ['name' => '2019/2020', 'code' => '2019'],
            ['name' => '2020/2021', 'code' => '2020'],
            ['name' => '2021/2022', 'code' => '2021'],
            ['name' => '2022/2023', 'code' => '2022'],
            ['name' => '2023/2024', 'code' => '2023'],
            ['name' => '2024/2025', 'code' => '2024'],
            ['name' => '2025/2026', 'code' => '2025'],
            ['name' => '2026/2027', 'code' => '2026'],
            ['name' => '2027/2028', 'code' => '2027'],
            ['name' => '2028/2029', 'code' => '2028'],
            ['name' => '2029/2030', 'code' => '2029'],
            ['name' => '2030/2031', 'code' => '2030'],
        ];

        $months = [
            1  => 'January',
            2  => 'February',
            3  => 'March',
            4  => 'April',
            5  => 'May',
            6  => 'June',
            7  => 'July',
            8  => 'August',
            9  => 'September',
            10 => 'October',
            11 => 'November',
            12 => 'December',
        ];

        foreach ($years as $year) {
            $yr = FinancialYear::query()->updateOrCreate($year);

            foreach ($months as $index => $month) {
                FinancialMonth::create([
                    'financial_year_id' => $yr->id,
                    'number'            => $index,
                    'name'              => $month,
                    'due_date'          => Carbon::create($year['code'], $index, 20)->toDateTimeString(),
                    'lumpsum_due_date'  => Carbon::create($year['code'], $index)->endOfMonth()->toDateTimeString(),
                    'is_approved' => 1,
                ]);
            }

            foreach($months as $index => $month){
                SevenDaysFinancialMonth::create([
                    'financial_year_id' => $yr->id,
                    'number' => $index,
                    'name' => $month,
                    'due_date' => Carbon::create($year['code'], $index, 7)->toDateTimeString(),
                    'is_approved' => 1,
                ]);
            }
        }
    }
}
