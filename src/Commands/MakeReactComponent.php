<?php

namespace Arandu\LaravelMuiAdmin\Commands;

use Illuminate\Console\Command;


class MakeReactComponent extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:react-component
                            {name : O nome do component}
                            {--prop-types : Ao utilizar esta flag, será criada a estrutura para validação de tipo das props}
                            {--page : Ao utilizar esta flag, criará o componente como Página}';
                            // {--connected : Ao utilizar esta flag, o componente virá conectado ao redux}

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        if (\App::environment('production')) {
            $this->error('Command not for production');

            return 1;
        }

        $name = $this->argument('name');

        $nameParts = collect(explode('/', $name));

        $componentName = $nameParts->last();

        $connected = false; // $this->option('connected');
        $propTypes = $this->option('prop-types');
        $page = $this->option('page');

        $folder = $page ? 'views' : 'components';

        if (file_exists(base_path() . "/resources/js/{$folder}/{$name}.jsx")) {
            $this->error('Já existe um componente com este nome.');

            return 1;
        }

        $filename = base_path() . "/resources/js/{$folder}/{$name}.jsx";

        $imports = '';
        $afterComponent = '';
        $exports = $connected ? "connect(mapStateToProps)({$componentName})" : $componentName;

        $wrapperOpenTag = '        <React.Fragment>';
        $wrapperCloseTag = '        </React.Fragment>';

        if ($propTypes) {
            $imports .= "import PropTypes from 'prop-types';\n";

            $afterComponent .= "{$componentName}.propTypes = {\n    // appIsLoaded: PropTypes.bool.isRequired\n};\n";
        }

        if ($page) {
            $imports .= "import Grid from '@mui/material/Unstable_Grid2';\n";

            $wrapperOpenTag = "        <Grid container disableEqualOverflow spacing={2}>\n            <Grid>";
            $wrapperCloseTag = "            </Grid>\n        </Grid>";
        }

        if ($connected) {
            $imports .= "import { connect } from 'react-redux';\n";

            $afterComponent .= "const mapStateToProps = (state) => ({\n    // appIsLoaded: state.app.loaded,\n});\n";
        }

        $fileContents = <<<EOT
import React from 'react';
{$imports}

const {$componentName} = () => {

    return (
{$wrapperOpenTag}
            Componente {$componentName}
{$wrapperCloseTag}
    );
};

{$afterComponent}

export default {$exports};

EOT;

        if (!\File::exists(dirname($filename))) {
            \File::makeDirectory(dirname($filename), 0755, true, true);
        }

        $written = file_put_contents($filename, $fileContents);

        if (!$written) {
            $this->error('Houve um erro ao gravar o arquivo');

            return 1;
        }
        $this->info('Componente criado com sucesso em ' . $filename);

        return 0;
    }
}