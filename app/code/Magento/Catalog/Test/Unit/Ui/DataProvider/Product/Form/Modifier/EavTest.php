<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Magento\Catalog\Test\Unit\Ui\DataProvider\Product\Form\Modifier;

use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Ui\DataProvider\Product\Form\Modifier\Eav;
use Magento\Eav\Model\Config;
use Magento\Framework\App\RequestInterface;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Store\Api\Data\StoreInterface;
use Magento\Ui\DataProvider\EavValidationRules;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\Collection as GroupCollection;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Group\CollectionFactory as GroupCollectionFactory;
use Magento\Eav\Model\Entity\Attribute\Group;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as EavAttribute;
use Magento\Eav\Model\Entity\Type as EntityType;
use Magento\Eav\Model\ResourceModel\Entity\Attribute\Collection as AttributeCollection;
use Magento\Ui\DataProvider\Mapper\FormElement as FormElementMapper;
use Magento\Ui\DataProvider\Mapper\MetaProperties as MetaPropertiesMapper;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Magento\Catalog\Api\ProductAttributeGroupRepositoryInterface;
use Magento\Framework\Api\SearchCriteria;
use Magento\Framework\Api\SortOrderBuilder;
use Magento\Catalog\Api\ProductAttributeRepositoryInterface;
use Magento\Framework\Api\SearchResultsInterface;
use Magento\Catalog\Api\Data\ProductAttributeInterface;
use Magento\Eav\Api\Data\AttributeGroupInterface;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Framework\Currency;
use Magento\Framework\Locale\Currency as CurrencyLocale;
Use Magento\Framework\TestFramework\Unit\Helper\ObjectManager;

/**
 * Class EavTest
 *
 * @method Eav getModel
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class EavTest extends AbstractModifierTest
{
    /**
     * @var Config|\PHPUnit_Framework_MockObject_MockObject
     */
    private $eavConfigMock;

    /**
     * @var EavValidationRules|\PHPUnit_Framework_MockObject_MockObject
     */
    private $eavValidationRulesMock;

    /**
     * @var RequestInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $requestMock;

    /**
     * @var GroupCollectionFactory|\PHPUnit_Framework_MockObject_MockObject
     */
    private $groupCollectionFactoryMock;

    /**
     * @var GroupCollection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $groupCollectionMock;

    /**
     * @var Group|\PHPUnit_Framework_MockObject_MockObject
     */
    private $groupMock;

    /**
     * @var EavAttribute|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeMock;

    /**
     * @var EntityType|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityTypeMock;

    /**
     * @var AttributeCollection|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeCollectionMock;

    /**
     * @var StoreManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $storeManagerMock;

    /**
     * @var FormElementMapper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $formElementMapperMock;

    /**
     * @var MetaPropertiesMapper|\PHPUnit_Framework_MockObject_MockObject
     */
    private $metaPropertiesMapperMock;

    /**
     * @var SearchCriteriaBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaBuilderMock;

    /**
     * @var ProductAttributeGroupRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeGroupRepositoryMock;

    /**
     * @var SearchCriteria|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchCriteriaMock;

    /**
     * @var SortOrderBuilder|\PHPUnit_Framework_MockObject_MockObject
     */
    private $sortOrderBuilderMock;

    /**
     * @var ProductAttributeRepositoryInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeRepositoryMock;

    /**
     * @var AttributeGroupInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $attributeGroupMock;

    /**
     * @var SearchResultsInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    private $searchResultsMock;

    /**
     * @var Attribute|\PHPUnit_Framework_MockObject_MockObject
     */
    private $eavAttributeMock;

    /**
     * @var StoreInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $storeMock;

    /**
     * @var Currency|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $currencyMock;

    /**
     * @var CurrencyLocale|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $currencyLocaleMock;

    /**
     * @var ObjectManager
     */
    protected $objectManager;
    
    /**
     * @var Eav
     */
    protected $eav;

    protected function setUp()
    {
        parent::setUp();
        $this->objectManager = new ObjectManager($this);
        $this->eavConfigMock = $this->getMockBuilder(Config::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->eavValidationRulesMock = $this->getMockBuilder(EavValidationRules::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->requestMock = $this->getMockBuilder(RequestInterface::class)
            ->getMockForAbstractClass();
        $this->groupCollectionFactoryMock = $this->getMockBuilder(GroupCollectionFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
        $this->groupCollectionMock =
            $this->getMockBuilder(GroupCollection::class)
                ->disableOriginalConstructor()
                ->getMock();
        $this->attributeMock = $this->getMockBuilder(EavAttribute::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->groupMock = $this->getMockBuilder(Group::class)
            ->disableOriginalConstructor()
            ->setMethods(['getAttributeGroupCode'])
            ->getMock();
        $this->entityTypeMock = $this->getMockBuilder(EntityType::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->attributeCollectionMock = $this->getMockBuilder(AttributeCollection::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->storeManagerMock = $this->getMockBuilder(StoreManagerInterface::class)
            ->getMockForAbstractClass();
        $this->formElementMapperMock = $this->getMockBuilder(FormElementMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->metaPropertiesMapperMock = $this->getMockBuilder(MetaPropertiesMapper::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->searchCriteriaBuilderMock = $this->getMockBuilder(SearchCriteriaBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->attributeGroupRepositoryMock = $this->getMockBuilder(ProductAttributeGroupRepositoryInterface::class)
            ->getMockForAbstractClass();
        $this->attributeGroupMock = $this->getMockBuilder(AttributeGroupInterface::class)
            ->setMethods(['getAttributeGroupCode', 'getApplyTo'])
            ->getMockForAbstractClass();
        $this->attributeRepositoryMock = $this->getMockBuilder(ProductAttributeRepositoryInterface::class)
            ->getMockForAbstractClass();
        $this->searchCriteriaMock = $this->getMockBuilder(SearchCriteria::class)
            ->disableOriginalConstructor()
            ->setMethods(['getItems'])
            ->getMock();
        $this->sortOrderBuilderMock = $this->getMockBuilder(SortOrderBuilder::class)
            ->disableOriginalConstructor()
            ->getMock();
        $this->searchResultsMock = $this->getMockBuilder(SearchResultsInterface::class)
            ->getMockForAbstractClass();
        $this->eavAttributeMock = $this->getMockBuilder(Attribute::class)
            ->setMethods(['getAttributeGroupCode', 'getApplyTo', 'getFrontendInput', 'getAttributeCode'])
            ->disableOriginalConstructor()
            ->getMock();

        $this->groupCollectionFactoryMock->expects($this->any())
            ->method('create')
            ->willReturn($this->groupCollectionMock);
        $this->groupCollectionMock->expects($this->any())
            ->method('setAttributeSetFilter')
            ->willReturnSelf();
        $this->groupCollectionMock->expects($this->any())
            ->method('setSortOrder')
            ->willReturnSelf();
        $this->groupCollectionMock->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $this->groupCollectionMock->expects($this->any())
            ->method('getIterator')
            ->willReturn(new \ArrayIterator([
                $this->groupMock,
            ]));
        $this->attributeCollectionMock->expects($this->any())
            ->method('addFieldToSelect')
            ->willReturnSelf();
        $this->attributeCollectionMock->expects($this->any())
            ->method('load')
            ->willReturnSelf();
        $this->eavConfigMock->expects($this->any())
            ->method('getEntityType')
            ->willReturn($this->entityTypeMock);
        $this->entityTypeMock->expects($this->any())
            ->method('getAttributeCollection')
            ->willReturn($this->attributeCollectionMock);
        $this->productMock->expects($this->any())
            ->method('getAttributes')
            ->willReturn([
                $this->attributeMock,
            ]);
        $this->storeMock = $this->getMockBuilder(StoreInterface::class)
            ->setMethods(['load', 'getId', 'getConfig', 'getBaseCurrencyCode'])
            ->getMockForAbstractClass();
        $this->currencyMock = $this->getMockBuilder(Currency::class)
            ->disableOriginalConstructor()
            ->setMethods(['toCurrency'])
            ->getMock();
        $this->currencyLocaleMock = $this->getMockBuilder(CurrencyLocale::class)
            ->disableOriginalConstructor()
            ->setMethods(['getCurrency'])
            ->getMock();
        
        $this->eav =$this->getModel();
        $this->objectManager->setBackwardCompatibleProperty(
            $this->eav,
            'localeCurrency',
            $this->currencyLocaleMock
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function createModel()
    {
        return $this->objectManager->getObject(Eav::class, [
            'locator' => $this->locatorMock,
            'eavValidationRules' => $this->eavValidationRulesMock,
            'eavConfig' => $this->eavConfigMock,
            'request' => $this->requestMock,
            'groupCollectionFactory' => $this->groupCollectionFactoryMock,
            'storeManager' => $this->storeManagerMock,
            'formElementMapper' => $this->formElementMapperMock,
            'metaPropertiesMapper' => $this->metaPropertiesMapperMock,
            'searchCriteriaBuilder' => $this->searchCriteriaBuilderMock,
            'attributeGroupRepository' => $this->attributeGroupRepositoryMock,
            'sortOrderBuilder' => $this->sortOrderBuilderMock,
            'attributeRepository' => $this->attributeRepositoryMock,
        ]);
    }

    public function testModifyData()
    {
        $sourceData = [
            '1' => [
                'product' => [
                    ProductAttributeInterface::CODE_PRICE => '19.99'
                ]
            ]
        ];

        $this->locatorMock->expects($this->any())
            ->method('getProduct')
            ->willReturn($this->productMock);

        $this->productMock->expects($this->any())
            ->method('getId')
            ->willReturn(1);
        $this->productMock->expects($this->once())
            ->method('getAttributeSetId')
            ->willReturn(4);
        $this->productMock->expects($this->once())
            ->method('getData')
            ->with(ProductAttributeInterface::CODE_PRICE)->willReturn('19.9900');

        $this->searchCriteriaBuilderMock->expects($this->any())
            ->method('addFilter')
            ->willReturnSelf();
        $this->searchCriteriaBuilderMock->expects($this->any())
            ->method('create')
            ->willReturn($this->searchCriteriaMock);
        $this->attributeGroupRepositoryMock->expects($this->any())
            ->method('getList')
            ->willReturn($this->searchCriteriaMock);
        $this->searchCriteriaMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$this->attributeGroupMock]);
        $this->sortOrderBuilderMock->expects($this->once())
            ->method('setField')
            ->willReturnSelf();
        $this->sortOrderBuilderMock->expects($this->once())
            ->method('setAscendingDirection')
            ->willReturnSelf();
        $dataObjectMock = $this->getMock('\Magento\Framework\Api\AbstractSimpleObject', [], [], '', false);
        $this->sortOrderBuilderMock->expects($this->once())
            ->method('create')
            ->willReturn($dataObjectMock);

        $this->searchCriteriaBuilderMock->expects($this->any())
            ->method('addFilter')
            ->willReturnSelf();
        $this->searchCriteriaBuilderMock->expects($this->once())
            ->method('addSortOrder')
            ->willReturnSelf();
        $this->searchCriteriaBuilderMock->expects($this->any())
            ->method('create')
            ->willReturn($this->searchCriteriaMock);

        $this->attributeRepositoryMock->expects($this->once())
            ->method('getList')
            ->with($this->searchCriteriaMock)
            ->willReturn($this->searchResultsMock);
        $this->eavAttributeMock->expects($this->any())
            ->method('getAttributeGroupCode')
            ->willReturn('product-details');
        $this->eavAttributeMock->expects($this->once())
            ->method('getApplyTo')
            ->willReturn([]);
        $this->eavAttributeMock->expects($this->once())
            ->method('getFrontendInput')
            ->willReturn('price');
        $this->eavAttributeMock->expects($this->any())
            ->method('getAttributeCode')
            ->willReturn(ProductAttributeInterface::CODE_PRICE);
        $this->searchResultsMock->expects($this->once())
            ->method('getItems')
            ->willReturn([$this->eavAttributeMock]);

        $this->storeMock->expects(($this->once()))
            ->method('getBaseCurrencyCode')
            ->willReturn('en_US');
        $this->storeManagerMock->expects($this->once())
            ->method('getStore')
            ->willReturn($this->storeMock);
        $this->currencyMock->expects($this->once())
            ->method('toCurrency')
            ->willReturn('19.99');
        $this->currencyLocaleMock->expects($this->once())
            ->method('getCurrency')
            ->willReturn($this->currencyMock);

        $this->assertEquals($sourceData, $this->eav->modifyData([]));
    }
}
