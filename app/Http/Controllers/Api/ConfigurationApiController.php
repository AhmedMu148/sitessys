<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ConfigurationService;
use App\Models\Site;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ConfigurationApiController extends Controller
{
    protected ConfigurationService $configService;

    public function __construct(ConfigurationService $configService)
    {
        $this->configService = $configService;
        // Apply auth middleware to all routes except schema endpoint and site lookup
        $this->middleware('auth:sanctum')->except(['getConfigurationSchema', 'getSiteByDomain']);
    }

    /**
     * Get configuration by type
     */
    public function getConfiguration(Request $request, string $type): JsonResponse
    {
        try {
            $siteId = $request->input('site_id');
            $site = $siteId ? Site::findOrFail($siteId) : null;
            
            $config = $site ? 
                $site->getConfiguration($type) : 
                $this->configService->get(1, $type); // Use default site ID 1

            return response()->json([
                'success' => true,
                'data' => $config,
                'type' => $type,
                'site_id' => $siteId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update configuration
     */
    public function updateConfiguration(Request $request, string $type): JsonResponse
    {
        try {
            $data = $request->validate([
                'config' => 'required|array',
                'site_id' => 'nullable|integer|exists:sites,id'
            ]);

            $siteId = $data['site_id'] ?? null;
            $site = $siteId ? Site::findOrFail($siteId) : null;

            // Validate configuration
            $isValid = $this->configService->validate($type, $data['config']);
            if (!$isValid) {
                return response()->json([
                    'success' => false,
                    'message' => 'Configuration validation failed',
                    'errors' => ['config' => ['Invalid configuration data']]
                ], 422);
            }

            // Update configuration
            if ($site) {
                $result = $site->setConfiguration($type, $data['config']);
            } else {
                $result = $this->configService->set(1, $type, $data['config']); // Use default site ID 1
            }

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configuration updated successfully',
                    'type' => $type,
                    'site_id' => $siteId
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to update configuration'
            ], 500);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all configurations
     */
    public function getAllConfigurations(Request $request): JsonResponse
    {
        try {
            $siteId = $request->input('site_id');
            $site = $siteId ? Site::findOrFail($siteId) : null;

            $configurations = [];
            $configTypes = ['theme', 'navigation', 'colors', 'language', 'seo', 'media', 'sections'];

            foreach ($configTypes as $type) {
                $configurations[$type] = $site ? 
                    $site->getConfiguration($type) : 
                    $this->configService->get(1, $type); // Use default site ID 1
            }

            return response()->json([
                'success' => true,
                'data' => $configurations,
                'site_id' => $siteId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve configurations',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Export configuration
     */
    public function exportConfiguration(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'site_id' => 'nullable|integer|exists:sites,id',
                'types' => 'nullable|array',
                'types.*' => 'string'
            ]);

            $siteId = $data['site_id'] ?? null;
            $types = $data['types'] ?? [];
            
            $export = $this->configService->export($types, $siteId);

            return response()->json([
                'success' => true,
                'data' => $export,
                'filename' => 'configuration_export_' . date('Y-m-d_H-i-s') . '.json'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to export configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Import configuration
     */
    public function importConfiguration(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'config_data' => 'required|array',
                'site_id' => 'nullable|integer|exists:sites,id',
                'overwrite' => 'boolean'
            ]);

            $siteId = $data['site_id'] ?? null;
            $overwrite = $data['overwrite'] ?? false;

            $result = $this->configService->import($data['config_data'], $overwrite, $siteId);

            if ($result['success']) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configuration imported successfully',
                    'imported_count' => $result['imported_count']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to import configuration',
                'errors' => $result['errors']
            ], 400);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to import configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Validate configuration
     */
    public function validateConfiguration(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'type' => 'required|string',
                'config' => 'required|array'
            ]);

            $isValid = $this->configService->validate($data['type'], $data['config']);

            return response()->json([
                'success' => true,
                'valid' => $isValid,
                'errors' => $isValid ? [] : ['config' => ['Validation failed']]
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to validate configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get configuration schema
     */
    public function getConfigurationSchema(Request $request, string $type): JsonResponse
    {
        try {
            $schema = $this->configService->getSchema($type);

            if ($schema) {
                return response()->json([
                    'success' => true,
                    'data' => $schema,
                    'type' => $type
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Schema not found for configuration type: ' . $type
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve schema',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Reset configuration to defaults
     */
    public function resetToDefaults(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'type' => 'required|string',
                'site_id' => 'nullable|integer|exists:sites,id'
            ]);

            $siteId = $data['site_id'] ?? null;
            $site = $siteId ? Site::findOrFail($siteId) : null;

            // Get default configuration
            $defaults = $this->configService->getDefaults($data['type']);
            
            // Apply defaults
            if ($site) {
                $result = $site->setConfiguration($data['type'], $defaults);
            } else {
                $result = $this->configService->set(1, $data['type'], $defaults); // Use default site ID 1
            }

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configuration reset to defaults successfully',
                    'type' => $data['type']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to reset configuration'
            ], 500);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to reset configuration',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get configuration versions/history
     */
    public function getConfigurationVersions(Request $request, string $type): JsonResponse
    {
        try {
            $siteId = $request->input('site_id');
            $site = $siteId ? Site::findOrFail($siteId) : null;

            $versions = $site ? 
                $site->getConfigurationVersions($type) : 
                $this->configService->getVersions(1, $type); // Use default site ID 1

            return response()->json([
                'success' => true,
                'data' => $versions,
                'type' => $type,
                'site_id' => $siteId
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve configuration versions',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Restore configuration from version
     */
    public function restoreConfigurationVersion(Request $request): JsonResponse
    {
        try {
            $data = $request->validate([
                'type' => 'required|string',
                'version_id' => 'required|string',
                'site_id' => 'nullable|integer|exists:sites,id'
            ]);

            $siteId = $data['site_id'] ?? null;
            $site = $siteId ? Site::findOrFail($siteId) : null;

            $result = $site ? 
                $site->restoreConfigurationVersion($data['type'], $data['version_id']) : 
                $this->configService->restoreVersion($data['type'], $data['version_id']);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Configuration restored from version successfully',
                    'type' => $data['type'],
                    'version_id' => $data['version_id']
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to restore configuration version'
            ], 500);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to restore configuration version',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get site information by domain (public endpoint)
     */
    public function getSiteByDomain(Request $request): JsonResponse
    {
        try {
            $domain = $request->input('domain');
            $subdomain = $request->input('subdomain');
            
            $site = null;
            
            if ($subdomain) {
                $site = Site::findBySubdomain($subdomain);
            } else if ($domain) {
                $site = Site::findByDomain($domain);
            } else {
                // If no parameters provided, return the first active site for testing
                $site = Site::where('status_id', true)->first();
            }

            if (!$site) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active site found'
                ], 404);
            }

            // Get domain data from JSON configuration
            $domainData = $site->getDomainData();

            return response()->json([
                'success' => true,
                'data' => [
                    'site' => [
                        'id' => $site->id,
                        'site_name' => $site->site_name,
                        'url' => $site->url,
                        'status_id' => $site->status_id,
                        'domains' => $domainData['domains'],
                        'subdomains' => $domainData['subdomains'],
                        'created_at' => $site->created_at,
                        'updated_at' => $site->updated_at
                    ],
                    'owner' => $site->user->only(['name', 'email']),
                    'configurations' => $this->configService->getAll($site->id)
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve site information',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current user's sites (authenticated endpoint)
     */
    public function getMySites(Request $request): JsonResponse
    {
        try {
            $user = $request->user();
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }

            $sites = Site::where('user_id', $user->id)->get();

            return response()->json([
                'success' => true,
                'data' => $sites->map(function ($site) {
                    $domainData = $site->getDomainData();
                    return [
                        'site' => [
                            'id' => $site->id,
                            'site_name' => $site->site_name,
                            'url' => $site->url,
                            'status_id' => $site->status_id,
                            'domains' => $domainData['domains'],
                            'subdomains' => $domainData['subdomains'],
                            'created_at' => $site->created_at,
                            'updated_at' => $site->updated_at
                        ],
                        'configurations' => $this->configService->getAll($site->id),
                        'admin_url' => url("/admin?site_id={$site->id}"),
                        'site_urls' => $domainData['domains']
                    ];
                })
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve user sites',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
