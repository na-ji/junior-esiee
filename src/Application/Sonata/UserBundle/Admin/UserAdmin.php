<?php

namespace Application\Sonata\UserBundle\Admin;

use Sonata\UserBundle\Admin\Model\UserAdmin as BaseAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\UserBundle\Model\UserInterface;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Symfony\Component\HttpKernel\Log\LoggerInterface;

class UserAdmin extends BaseAdmin
{
    protected $tokenGenerator;
    protected $mailer;
    protected $logger;

    /**
     * @param TokenGeneratorInterface $tokenGenerator
     */
    public function setTokenGenerator(TokenGeneratorInterface $tokenGenerator)
    {
        $this->tokenGenerator = $tokenGenerator;
    }

    /**
     * @param MailerInterface $mailer
     */
    public function setMailer(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('username')
                ->add('email')
            ->with('Dossier')
                ->add('isComplete', 'sonata_type_boolean', array(
                    'expanded' => true
                ))
                ->add('registredAt')
                ->add('lastname', null, array('required' => false))
                ->add('firstname', null, array('required' => false))
                ->add('gender', 'sonata_user_gender', array(
					'required'           => false,
					'translation_domain' => $this->getTranslationDomain(),
					'expanded'           => true,
					'empty_value'        => false
                ))
                ->add('promo', 'choice', array(
				    'choices'  => array(
				    	'E1' => 'E1', 
				    	'E2' => 'E2', 
				    	'E3' => 'E3', 
				    	'E4' => 'E4', 
				    	'E5' => 'E5', 
				    ),
					'expanded'    => true,
					'required'    => false,
					'empty_value' => false
			    ))
                ->add('phone', null, array('required' => false))
                ->add('address')
                ->add('zipCode')
                ->add('city')
                ->add('birthCity')
                ->add('birthDepartment')
                ->add('nationality')
                ->add('socialSecurityNumber')
                ->add('socialSecurityCenter', 'choice', array(
				    'choices'  => array(
						'LMDE'   => 'LMDE', 
						'SMEREP' => 'SMEREP'
				    ),
					'expanded'    => true,
					'required'    => false,
					'empty_value' => false
			    ))
                ->reorder(array(
		        	'isComplete', 'registredAt', 'lastname', 'firstname', 'gender', 'promo', 'phone', 'address', 'zipCode', 'city', 'birthCity', 'birthDepartment', 'nationality', 'socialSecurityNumber', 'socialSecurityCenter'
		    	))
            ->end()
            ->with('Pièces Jointes')
                ->add('cv', 'sonata_media_type', array(
                    'required'      => false,
                    'provider'      => 'sonata.media.provider.file',
                    'context'       => 'attachment',
                    'new_on_update' => false,
                ))
                ->add('socialSecurity', 'sonata_media_type', array(
                    'required'      => false,
                    'provider'      => 'sonata.media.provider.file',
                    'context'       => 'attachment',
                    'new_on_update' => false,
                ))
                ->add('chequeNumber')
            ->end()
            ->with('Groups')
                ->add('groups', 'sonata_type_model', array(
                    'required' => false,
                    'expanded' => true,
                    'multiple' => true
                ))
            ->end()
        ;

        if ($this->getSubject() && !$this->getSubject()->hasRole('ROLE_SUPER_ADMIN')) {
            $formMapper
                ->with('Management')
                    ->add('realRoles', 'sonata_security_roles', array(
                        'label'    => 'form.label_roles',
                        'expanded' => true,
                        'multiple' => true,
                        'required' => false
                    ))
                    ->add('locked', null, array('required' => false))
                    ->add('expired', null, array('required' => false))
                    ->add('enabled', null, array('required' => false))
                    ->add('credentialsExpired', null, array('required' => false))
                ->end()
            ;
        }

        $formMapper
            ->with('Security')
                ->add('token', null, array('required' => false))
                ->add('twoStepVerificationCode', null, array('required' => false))
            ->end()
        ;

        if (!$this->getSubject() || is_null($this->getSubject()->getId())) {
            $formMapper
                ->add('plainPassword', 'hidden', array(
                    'required' => false,
                    'data'     => 'caca'
                ))
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('username')
            ->add('email')
            ->add('groups')
            ->add('isComplete')
            ->add('registredAt')
        ;

        if ($this->isGranted('ROLE_ALLOWED_TO_SWITCH')) {
            $listMapper
                ->add('impersonating', 'string', array('template' => 'SonataUserBundle:Admin:Field/impersonating.html.twig'))
            ;
        }
    }

    /**
     * {@inheritdoc}
     */
    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->with('General')
                ->add('username')
                ->add('email')
            ->end()
            ->with('Dossier')
                ->add('isComplete')
                ->add('registredAt')
                ->add('lastname')
                ->add('firstname')
                ->add('gender')
                ->add('promo')
                ->add('phone')
                ->add('address')
                ->add('zipCode')
                ->add('city')
                ->add('birthCity')
                ->add('birthDepartment')
                ->add('nationality')
                ->add('socialSecurityNumber')
                ->add('socialSecurityCenter')
                ->reorder(array(
                    'isComplete', 'registredAt', 'lastname', 'firstname', 'gender', 'promo', 'phone', 'address', 'zipCode', 'city', 'birthCity', 'birthDepartment', 'nationality', 'socialSecurityNumber', 'socialSecurityCenter'
                ))
            ->end()
            ->with('Pièces Jointes')
                ->add('cv')
                ->add('socialSecurity')
                ->add('chequeNumber')
            ->end()
            ->with('Groups')
                ->add('groups')
            ->end()
            ->with('Security')
                ->add('token')
                ->add('twoStepVerificationCode')
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureDatagridFilters(DatagridMapper $filterMapper)
    {
        $filterMapper
            ->add('id')
            ->add('username')
            ->add('isComplete')
            ->add('email')
            ->add('groups')
        ;
    }

    public function prePersist($user)
    {
        $token = $this->tokenGenerator->generateToken();
        $user->setConfirmationToken($token);
        $user->setPlainPassword($token);
        $user->setHasPassword(false);
        $user->setEnabled(false);
        $user->setPasswordRequestedAt(new \DateTime());
        $this->mailer->sendCreatingPasswordEmailMessage($user);
    }
}