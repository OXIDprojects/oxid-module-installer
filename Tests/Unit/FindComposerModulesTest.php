<?php
namespace OxidCommunity\ModuleInstaller\Tests;


use OxidCommunity\ModuleInstaller\OxidComposerModulesService;

class FindComposerModulesTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testFindComposerModules(){
        $sut = new OxidComposerModulesService();
        $packages  = $sut->getOxidModulePackages();
        $this->assertArrayHasKey('moduleinstaller',$packages);
    }
}