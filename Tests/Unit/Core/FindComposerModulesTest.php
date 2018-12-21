<?php
namespace OxidCommunity\ModuleInstaller\Tests\Unit\Core;


use OxidCommunity\ModuleInstaller\OxidComposerModulesService;

class FindComposerModulesTest extends \OxidEsales\TestingLibrary\UnitTestCase
{
    public function testGetOxidComposerPackages(){
        $sut = new OxidComposerModulesService();
        $packages  = $sut->getOxidModulePackages();
        //currently about 7 oxid modules are installed by default
        $this->assertLessThan(20, count($packages));
    }

    public function testGetComposerPackages(){
        $sut = new OxidComposerModulesService();
        $packages  = $sut->getPackages();
        $this->assertTrue(count($packages) > 10);
    }

    public function testFindOxidModuleComposerPackages(){
        $sut = new OxidComposerModulesService();
        $packages  = $sut->getList();
        $this->assertArrayHasKey('moduleinstaller',$packages);
    }
}