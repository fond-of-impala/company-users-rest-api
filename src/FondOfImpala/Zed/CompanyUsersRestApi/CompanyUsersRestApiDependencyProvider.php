<?php

declare(strict_types = 1);

namespace FondOfImpala\Zed\CompanyUsersRestApi;

use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyBusinessUnitFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyRoleFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCompanyUserReferenceFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToCustomerFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Facade\CompanyUsersRestApiToPermissionFacadeBridge;
use FondOfImpala\Zed\CompanyUsersRestApi\Dependency\Service\CompanyUsersRestApiToUtilTextServiceBridge;
use Orm\Zed\CompanyRole\Persistence\SpyCompanyRoleToCompanyUserQuery;
use Orm\Zed\CompanyUser\Persistence\SpyCompanyUserQuery;
use Propel\Runtime\ActiveQuery\Criteria;
use Spryker\Zed\Kernel\AbstractBundleDependencyProvider;
use Spryker\Zed\Kernel\Container;

/**
 * @codeCoverageIgnore
 */
class CompanyUsersRestApiDependencyProvider extends AbstractBundleDependencyProvider
{
    /**
     * @var string
     */
    public const FACADE_COMPANY = 'FACADE_COMPANY';

    /**
     * @var string
     */
    public const FACADE_COMPANY_BUSINESS_UNIT = 'FACADE_COMPANY_BUSINESS_UNIT';

    /**
     * @var string
     */
    public const FACADE_COMPANY_ROLE = 'FACADE_COMPANY_ROLE';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER = 'FACADE_COMPANY_USER';

    /**
     * @var string
     */
    public const FACADE_COMPANY_USER_REFERENCE = 'FACADE_COMPANY_USER_REFERENCE';

    /**
     * @var string
     */
    public const FACADE_CUSTOMER = 'FACADE_CUSTOMER';

    /**
     * @var string
     */
    public const FACADE_PERMISSION = 'FACADE_PERMISSION';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COMPANY_USER = 'PROPEL_QUERY_COMPANY_USER';

    /**
     * @var string
     */
    public const PROPEL_QUERY_COMPANY_ROLE_TO_COMPANY_USER = 'PROPEL_QUERY_COMPANY_ROLE_TO_COMPANY_USER';

    /**
     * @var string
     */
    public const SERVICE_UTIL_TEXT = 'SERVICE_UTIL_TEXT';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_USER_POST_CREATE = 'PLUGINS_COMPANY_USER_POST_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_USER_PRE_DELETE_VALIDATION = 'PLUGINS_COMPANY_USER_PRE_DELETE_VALIDATION';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_USER_PRE_UPDATE_VALIDATION = 'PLUGINS_COMPANY_USER_PRE_UPDATE_VALIDATION';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_USER_PRE_CREATE = 'PLUGINS_COMPANY_USER_PRE_CREATE';

    /**
     * @var string
     */
    public const PLUGINS_COMPANY_USER_QUERY_EXPANDER = 'PLUGINS_COMPANY_USER_QUERY_EXPANDER';

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function provideBusinessLayerDependencies(Container $container): Container
    {
        $container = parent::provideBusinessLayerDependencies($container);

        $container = $this->addCompanyBusinessUnitFacade($container);
        $container = $this->addCompanyFacade($container);
        $container = $this->addCompanyRoleFacade($container);
        $container = $this->addCompanyUserFacade($container);
        $container = $this->addCompanyUserReferenceFacade($container);
        $container = $this->addCustomerFacade($container);
        $container = $this->addUtilTextService($container);
        $container = $this->addPermissionFacade($container);
        $container = $this->addCompanyUserPostCreatePlugin($container);
        $container = $this->addCompanyUserPreDeleteValidationPlugin($container);
        $container = $this->addCompanyUserPreUpdateValidationPlugin($container);

        return $this->addCompanyUserPreCreatePlugins($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function providePersistenceLayerDependencies(Container $container): Container
    {
        $container = parent::providePersistenceLayerDependencies($container);

        $container = $this->addCompanyRoleToCompanyUserPropelQuery($container);
        $container = $this->addCompanyUserQueryExpanderPlugins($container);

        return $this->addCompanyUserPropelQuery($container);
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_COMPANY_USER] = static fn (): Criteria => SpyCompanyUserQuery::create();

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyRoleToCompanyUserPropelQuery(Container $container): Container
    {
        $container[static::PROPEL_QUERY_COMPANY_ROLE_TO_COMPANY_USER] = static fn (): Criteria => SpyCompanyRoleToCompanyUserQuery::create();

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyRoleFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_ROLE] = static fn (Container $container): CompanyUsersRestApiToCompanyRoleFacadeBridge => new CompanyUsersRestApiToCompanyRoleFacadeBridge(
            $container->getLocator()->companyRole()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addUtilTextService(Container $container): Container
    {
        $container[static::SERVICE_UTIL_TEXT] = static fn (Container $container): CompanyUsersRestApiToUtilTextServiceBridge => new CompanyUsersRestApiToUtilTextServiceBridge(
            $container->getLocator()->utilText()->service(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCustomerFacade(Container $container): Container
    {
        $container[static::FACADE_CUSTOMER] = static fn (Container $container): CompanyUsersRestApiToCustomerFacadeBridge => new CompanyUsersRestApiToCustomerFacadeBridge(
            $container->getLocator()->customer()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY] = static fn (Container $container): CompanyUsersRestApiToCompanyFacadeBridge => new CompanyUsersRestApiToCompanyFacadeBridge(
            $container->getLocator()->company()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyBusinessUnitFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_BUSINESS_UNIT] = static fn (Container $container): CompanyUsersRestApiToCompanyBusinessUnitFacadeBridge => new CompanyUsersRestApiToCompanyBusinessUnitFacadeBridge(
            $container->getLocator()->companyBusinessUnit()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_USER] = static fn (Container $container): CompanyUsersRestApiToCompanyUserFacadeBridge => new CompanyUsersRestApiToCompanyUserFacadeBridge(
            $container->getLocator()->companyUser()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addCompanyUserReferenceFacade(Container $container): Container
    {
        $container[static::FACADE_COMPANY_USER_REFERENCE] = static fn (Container $container): CompanyUsersRestApiToCompanyUserReferenceFacadeBridge => new CompanyUsersRestApiToCompanyUserReferenceFacadeBridge(
            $container->getLocator()->companyUserReference()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    protected function addPermissionFacade(Container $container): Container
    {
        $container[static::FACADE_PERMISSION] = static fn (Container $container): CompanyUsersRestApiToPermissionFacadeBridge => new CompanyUsersRestApiToPermissionFacadeBridge(
            $container->getLocator()->permission()->facade(),
        );

        return $container;
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyUserPostCreatePlugin(Container $container): Container
    {
        $self = $this;

        $container[static::PLUGINS_COMPANY_USER_POST_CREATE] = static fn (): array => $self->getCompanyUserPostCreatePlugins();

        return $container;
    }

    /**
     * @return array<\FondOfOryx\Zed\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserPostCreatePluginInterface>
     */
    protected function getCompanyUserPostCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyUserPreDeleteValidationPlugin(Container $container): Container
    {
        $self = $this;

        $container[static::PLUGINS_COMPANY_USER_PRE_DELETE_VALIDATION] = static fn (): array => $self->getCompanyUserPreDeleteValidationPlugins();

        return $container;
    }

    /**
     * @return array<\FondOfOryx\Zed\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserPreDeleteValidationPluginInterface>
     */
    protected function getCompanyUserPreDeleteValidationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyUserPreUpdateValidationPlugin(Container $container): Container
    {
        $self = $this;

        $container[static::PLUGINS_COMPANY_USER_PRE_UPDATE_VALIDATION] = static fn (): array => $self->getCompanyUserPreUpdateValidationPlugins();

        return $container;
    }

    /**
     * @return array<\FondOfOryx\Zed\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserPreUpdateValidationPluginInterface>
     */
    protected function getCompanyUserPreUpdateValidationPlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyUserPreCreatePlugins(Container $container): Container
    {
        $self = $this;

        $container[static::PLUGINS_COMPANY_USER_PRE_CREATE] = static fn (): array => $self->getCompanyUserPreCreatePlugins();

        return $container;
    }

    /**
     * @return array<\FondOfOryx\Zed\CompanyUsersRestApiExtension\Dependency\Plugin\CompanyUserPreCreatePluginInterface>
     */
    protected function getCompanyUserPreCreatePlugins(): array
    {
        return [];
    }

    /**
     * @param \Spryker\Zed\Kernel\Container $container
     *
     * @return \Spryker\Zed\Kernel\Container
     */
    public function addCompanyUserQueryExpanderPlugins(Container $container): Container
    {
        $self = $this;

        $container[static::PLUGINS_COMPANY_USER_QUERY_EXPANDER] = static fn (): array => $self->getCompanyUserQueryExpanderPlugins();

        return $container;
    }

    /**
     * @return array<\FondOfOryx\Zed\CompanyUsersRestApiExtension\Dependency\Plugin\QueryExpander\CompanyUserQueryExpanderPluginInterface>
     */
    protected function getCompanyUserQueryExpanderPlugins(): array
    {
        return [];
    }
}
