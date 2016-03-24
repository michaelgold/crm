<?php

namespace Empiricly\Bundle\AppBundle\EventListener;

use Oro\Bundle\DataGridBundle\Event\BuildBefore;

class GridListener
{
    public function onBuildBefore(BuildBefore $event)
    {
	$grid = $event->getDatagrid();

        if ($grid->getName() == 'contacts-grid') {
            
            $grid->getConfig()->addColumn(
		'jobTitle', 
		['label' => 'job title'], 
		'c.jobTitle',
		['data_name' => 'c.jobTitle'],
		['data_name' => 'c.jobTitle', 'type' => 'string']
	    );
        }
    }
}
