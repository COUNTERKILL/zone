<?php
// ������
Class Model_Autorization{
	
	public function Login()
	{
		$user = (empty($_POST['user_name'])) ? '' : $_POST['user_name'];
		$pass = (empty($_POST['user_password'])) ? '' : $_POST['user_password'];
		$Player=new TPlayer($user, $pass, true); // ������ �������� �������,��� ��� �����������
		if(!$Player->autorized) return $Player->status;
		return '';
	}	
	
}
?>