<?php

// src/Algo/SiteBundle/Form/BlogType.php

namespace Algo\SiteBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Genemu\Bundle\FormBundle\Form\Core\Type;

class BlogType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title');
		$builder->add('author');
		//$builder->add('image','file',array(
          //      'data_class' => null
            //));
        $builder->add('imagefile');    
        
		$builder->add('blog',  'genemu_tinymce');
		$builder->add('brief', 'genemu_tinymce');
		
		$builder->add('tags');
	}

	public function getName()
	{
		return 'blog';
	}
}