<?php

// Author: Melnikov Savva, 2025.
// Changes:
//     14.05.2025: Added calcBonus function
//     15.05.2025: Added PHPDoc
class Bonus
{
	const MANAGER = 'manager';

	static function notify($empId, $empName, $email, $bonus, $sendMail = true, $sendPush = true)
	{
		if ($sendPush)
		{
			$text = $empName . ', your bonus is ' . $bonus;
			push($empId, $text);
		}

		if ($sendMail)
		{
			$text = $empName . ', your bonus is ' . $bonus;
			mail($email, 'bonus', $text);
		}
	}

	// Функция расчёта бонусов сотрудникам
	static function bonusCalc($employees, $doNotify = false)
	{
		$result = [];
		foreach ($employees as $emp)
		{
			// Проверка, если стаж больше 5 лет, бонус 20%, иначе 10%
			if ($emp['experience'] > 5)
			{
				$bonus = $emp['salary'] * 0.2;
			}
			else
			{
				$bonus = $emp['salary'] * 0.1;
			}
			// Дополнительная премия для менеджеров (должность 'manager')
			if ($emp['position'] == self::MANAGER)
			{
				$bonus += 500;
			}
			// Если зарплата меньше 3000, добавляем фиксированную сумму
			if ($emp['salary'] < 3000)
			{
				$bonus += 200;
			}
			$result[] = [ 'id' => $emp['id'], 'bonus' => $bonus, 'name' => $emp['name'] ];

			if ($doNotify)
			{
				self::notify($emp['id'], $emp['name'], $emp['email'], $bonus);
			}
		}

		return $result;
	}

	/**
	 * @param array $employee
 	 * [
	 * 	id = int required
	 * 	name = string
	 * 	mail = string
	 * 	position = string
  	 *	salary = int
    	 *	experience = int
	 * ]
	 * @return string
	 */
	private function getOvertimeBonus(array $employee): int
	{
		if ($employee['workhours'] > 40)
		{
			return 100;
		}

		return 0;
	}
}


// Пример данных сотрудников
$calculator = new \Bonus();
$employees = [
	[ 'id' => 1, 'name' => 'Иван', 'mail' => '123@mail.ru', 'position' => 'developer', 'salary' => 2500, 'experience' => 3 ],
	[ 'id' => 2, 'name' => 'Анна', 'mail' => '321@mail.ru', 'position' => 'manager', 'salary' => 3500, 'experience' => 7 ],
	[ 'id' => 3, 'name' => 'Пётр', 'mail' => '231@mail.ru', 'position' => 'developer', 'salary' => 3200, 'experience' => 6 ],
];

print_r($calculator::bonusCalc($employees));
?>
