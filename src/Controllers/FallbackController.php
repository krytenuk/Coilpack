<?php

namespace Expressionengine\Coilpack\Controllers;

use Expressionengine\Coilpack\Bootstrap;
use ExpressionEngine\Core;
use Illuminate\Support\Facades\Request;

class FallbackController
{
    public function index()
    {
        $assetFolders = [
            'themes',
            'images',
        ];

        if (in_array(Request::segment(1), $assetFolders)) {
            (new Bootstrap\LoadExpressionEngine)->asset()->bootstrapDependencies(app());

            return (new AssetController)();
        } else {
            $core = (new Bootstrap\LoadExpressionEngine)->page()->bootstrap(app());

            $request = Core\Request::fromGlobals();

            // (new Bootstrap\LoadAddonFiles)->bootstrap(app());
            // (new Bootstrap\ReplaceTemplateTags)->bootstrap(app());

            return $core->runGlobal();
        }
    }
}