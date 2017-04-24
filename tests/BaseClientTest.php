<?php

namespace Klever\JustGivingApiSdk\Tests;

use Klever\JustGivingApiSdk\ResourceClients\BaseClient;
use ReflectionClass;

class BaseClientTest extends Base
{
    protected $childApi;

    protected $childClients = ['Account', 'Campaign', 'Charity', 'Countries', 'Currency', 'Donation', 'Event', 'Fundraising', 'Leaderboard', 'OneSearch', 'Project', 'Search', 'Sms', 'Team'];

    public function setUp()
    {
        $this->childApi = new BaseClientChild($this->guzzleClient);
    }

    /** @test */
    public function all_method_aliases_refer_to_actual_methods()
    {
        // Cycle through all API client classes
        foreach ($this->childClients as $childClient) {
            $className = '\\Klever\\JustGivingApiSdk\\ResourceClients\\' . $childClient . 'Client';
            $object = new $className($this->client);

            // Get the protected alias properties via reflection
            $aliases = $this->exposeProperty($object, 'aliases');

            foreach ($aliases as $method => $alias) {
                // Check that the aliased methods actually exist, dump out if not.
                if ( ! method_exists($object, $method)) {
                    var_dump(get_class($object), $method);
                }

                $this->assertTrue(method_exists($object, $method));
            }
        }
    }

    /**
     * Use Reflection to obtain the value of a property on an object.
     *
     * @param object $object
     * @param string $propertyName
     * @return mixed
     */
    protected function exposeProperty($object, $propertyName)
    {
        $property = (new ReflectionClass($object))->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /** @test */
    public function a_client_method_can_have_one_alias()
    {
        $result = $this->childApi->MethodOneAlias();

        $this->assertEquals('Method One', $result);
    }

    /** @test */
    public function a_client_method_can_have_multiple_aliases()
    {
        $result = $this->childApi->MethodTwoAliasOne();
        $resultTwo = $this->childApi->MethodTwoAliasTwo();

        $this->assertEquals('Method Two', $result);
        $this->assertEquals('Method Two', $resultTwo);
    }

    /** @test */
    public function a_client_method_can_be_called_in_any_case()
    {
        $resultOne = $this->childApi->METHOD_ONE();
        $resultTwo = $this->childApi->MethodOne();
        $resultThree = $this->childApi->method_two();

        $this->assertEquals('Method One', $resultOne);
        $this->assertEquals('Method One', $resultTwo);
        $this->assertEquals('Method Two', $resultThree);
    }

    /** @test */
    public function a_method_alias_can_be_called_in_any_case()
    {
        $resultOne = $this->childApi->method_one_Alias();
        $resultTwo = $this->childApi->methodTwoAliasOne();

        $this->assertEquals('Method One', $resultOne);
        $this->assertEquals('Method Two', $resultTwo);
    }
}

class BaseClientChild extends BaseClient
{
    protected $aliases = [
        'methodOne' => 'MethodOneAlias',
        'methodTwo' => ['MethodTwoAliasOne', 'MethodTwoAliasTwo'],
    ];

    public function methodOne()
    {
        return 'Method One';
    }

    public function methodTwo()
    {
        return 'Method Two';
    }
}
