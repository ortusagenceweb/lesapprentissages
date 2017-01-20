<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
	public function __construct($environment, $debug)
	{
		date_default_timezone_set( 'Europe/Paris' );
		parent::__construct($environment, $debug);
	}
	
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new LAP\AccueilBundle\LAPAccueilBundle(),
            new LAP\BlogBundle\LAPBlogBundle(),
			new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new LAP\EcoleBundle\LAPEcoleBundle(),
            new LAP\EquipeBundle\LAPEquipeBundle(),
            new LAP\InfosBundle\LAPInfosBundle(),
            new LAP\ContactBundle\LAPContactBundle(),
            new LAP\MentionsBundle\LAPMentionsBundle(),
			//new FOS\UserBundle\FOSUserBundle(),
            new LAP\UserBundle\LAPUserBundle(),
            new LAP\AdminBundle\LAPAdminBundle(),
            new LAP\UtilisateurBundle\LAPUtilisateurBundle(),
			new Ivory\CKEditorBundle\IvoryCKEditorBundle(),
			new FM\ElfinderBundle\FMElfinderBundle(),
            new LAP\MessagerieBundle\LAPMessagerieBundle(),
            new LAP\NotificationsBundle\LAPNotificationsBundle(),
            new LAP\StaticpagesBundle\LAPStaticpagesBundle(),
			new AncaRebeca\FullCalendarBundle\FullCalendarBundle(),
            new LAP\CalendarBundle\LAPCalendarBundle(),
            new LAP\TodoBundle\LAPTodoBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function getRootDir()
    {
        return __DIR__;
    }

    public function getCacheDir()
    {
        return dirname(__DIR__).'/var/cache/'.$this->getEnvironment();
    }

    public function getLogDir()
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir().'/config/config_'.$this->getEnvironment().'.yml');
    }
}
