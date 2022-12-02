<?php

namespace Laravel\VaporCli\BuildProcess;

use Laravel\VaporCli\Docker;
use Laravel\VaporCli\Helpers;
use Laravel\VaporCli\Manifest;

class BuildContainerImage
{
    use ParticipatesInBuildProcess {
        ParticipatesInBuildProcess::__construct as baseConstructor;
    }

    /**
     * The Docker build arguments.
     *
     * @var array
     */
    protected $buildArgs;

    /**
     * The Docker build flags.
     *
     * @var array
     */
    protected $cliFlags;

    /**
     * Create a new project builder.
     *
     * @param  string|null  $environment
     * @param  array  $buildArgs
     * @param  array  $cliFlags
     * @return void
     */
    public function __construct($environment = null, $buildArgs = [], $cliFlags = [])
    {
        $this->baseConstructor($environment);

        $this->buildArgs = $buildArgs;

        $this->cliFlags = $cliFlags;
    }

    /**
     * Execute the build process step.
     *
     * @return void
     */
    public function __invoke()
    {
        if (! Manifest::usesContainerImage($this->environment)) {
            return;
        }

        Helpers::step('<options=bold>Building Container Image</>');

        Docker::build($this->appPath, Manifest::name(), $this->environment, $this->buildArgs, $this->cliFlags);
    }

    /**
     * Get the image tag name.
     *
     * @return string
     */
    protected function getTagName()
    {
        return Manifest::name().':'.$this->environment;
    }
}
