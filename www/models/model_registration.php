<?php
// ������
Class Model_Registration{
	
	public function Reg()
	{
		$user = (empty($_POST['user_name'])) ? '' : $_POST['user_name'];
		$pass = (empty($_POST['user_password'])) ? '' : $_POST['user_password'];
		$Player=new TPlayer($user, $pass, false); // ������ �������� �������, ��� ��� �����������
		if(!$Player->autorized) return $Player->status;
		return '';
	}	
	
}
?>