<?php

namespace SS6\AdminBundle\Form\Login;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LoginFormType extends AbstractType {
	
	

	/**
	 * @param \Symfony\Component\Form\FormBuilderInterface $builder
	 * @param array $options
	 */
	public function buildForm(FormBuilderInterface $builder, array $options) {		
		$builder
			->add('username', 'text')
			->add('password', 'password')
			->add('login', 'submit');
	}

	/**
	 * @return string
	 */
	public function getName() {
		return 'admin_login';
	}

	/**
	 * @param array $options
	 * @return array
	 */
	public function getDefaultOptions(array $options) {
		return array(
			'data_class' => 'SS6\\ShopBundle\\Model\\Administrator\\Entity\\Administrator',
		);
	}

}
