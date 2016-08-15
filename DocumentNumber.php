<?php
namespace Resources;

/**
 * Validate the Brazilian Business and Social Numbers.
 * -- for a while only validate CNPJ and CPF.
 * -- some functions are provided for some other programmer... Thanks guys.
 * @author  Marcos Freitas <marcosfreitas@c4network.com.br>
 * @version  1.0 First release
 */

abstract class DocumentNumber {

	/**
	 * Check if is a valid CNPJ
	 * @author Guilherme Sehn https://gist.github.com/guisehn/
	 * @param  string $cnpj
	 * @return integer The Number without masks
	 */
	public static function isCNPJ($cnpj) {
		$cnpj = preg_replace('#[^0-9]#', '', (string) $cnpj);

		# check lenght
		if (strlen($cnpj) != 14)
			return false;

		# check the first verificator digit
		for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;

		if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
			return false;

		# check the second verificator digit
		for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;

		if ($cnpj{13} == ($resto < 2 ? 0 : 11 - $resto)) {
			return $cnpj;
		}

		return null;
	}

	/**
	 * Check if is a valid CPF
	 * @author  http://www.geradorcpf.com
	 * @param  string  $cpf
	 * @return integer The Number without masks
	 */
	public static function isCPF($cpf) {

		# Verifica se um número foi informado
		if(empty($cpf)) {
			return null;
		}

		# Elimina possivel mascara
		$cpf = preg_replace('#[^0-9]#', '', $cpf);
		$cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);

		# Verifica se o numero de digitos informados é igual a 11
		if (strlen($cpf) != 11) {
			return null;
		}
		# Verifica se nenhuma das sequências invalidas abaixo
		# foi digitada. Caso afirmativo, retorna falso
		else if ($cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
			return null;
		# Calcula os digitos verificadores para verificar se o
		# CPF é válido
		} else {

			for ($t = 9; $t < 11; $t++) {

				for ($d = 0, $c = 0; $c < $t; $c++) {
					$d += $cpf{$c} * (($t + 1) - $c);
				}

				$d = ((10 * $d) % 11) % 10;

				if ($cpf{$c} != $d) {
					return null;
				}
			}

			return $cpf;
		}

	}

	/**
	 * Can't check if is a valid RG
	 * -- the number is variable and the  varificator digit is mutable
	 * @param  string  $rg
	 * @return boolean
	 */
	/*public static function isRG($rg) {

		return null;
	}*/

}
