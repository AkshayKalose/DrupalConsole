<?php

/**
 * @file
 * Contains Drupal\Console\Command\ServicesTrait.
 */

namespace Drupal\Console\Command;

use Drupal\Console\Style\DrupalStyle;

trait ServicesTrait
{
    /**
     * @param DrupalStyle $io
     *
     * @return mixed
     */
    public function servicesQuestion(DrupalStyle $io)
    {
        if ($io->confirm(
            $this->trans('commands.common.questions.services.confirm'),
            false
        )) {
            $service_collection = [];
            $io->writeln($this->trans('commands.common.questions.services.message'));

            $services = $this->getServices();
            while (true) {
                $service = $io->choiceNoList(
                    $this->trans('commands.common.questions.services.name'),
                    $services,
                    null,
                    true
                );

                $service = trim($service);
                if (empty($service)) {
                    break;
                }

                array_push($service_collection, $service);
                $service_key = array_search($service, $services, true);

                if ($service_key >= 0) {
                    unset($services[$service_key]);
                }
            }

            return $service_collection;
        }
    }

    /**
     * @param array $services
     *
     * @return array
     */
    public function buildServices($services)
    {
        if (!empty($services)) {
            $build_service = [];
            foreach ($services as $service) {
                $class = get_class($this->getContainer()->get($service));
                $explode_class = explode('\\', $class);
                $build_service[$service] = [
                  'name' => $service,
                  'machine_name' => str_replace('.', '_', $service),
                  'class' => $class,
                  'short' => end($explode_class),
                ];
            }

            return $build_service;
        }

        return;
    }
}
