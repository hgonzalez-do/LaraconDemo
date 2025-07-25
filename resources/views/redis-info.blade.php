<x-header />
       
<x-top-menu />

<div class="max-w-6xl mx-auto ">
    <h1 class="text-2xl font-bold mb-6">Valkey Deployment Details</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        {{-- General Info --}}
        <x-info-card title="Server">
            <x-info-field label="Name" :value="$info['server_name']" />
            <x-info-field label="Mode" :value="$info['server_mode']" />
            <x-info-field label="OS" :value="$info['os']" />
            <x-info-field label="Architecture" :value="$info['arch_bits'] . '-bit'" />
            <x-info-field label="Port" :value="$info['tcp_port']" />
            <x-info-field label="Process ID" :value="$info['process_id']" />
        </x-info-card>

        {{-- Version Info --}}
        <x-info-card title="Versions">
            <x-info-field label="Valkey" :value="$info['valkey_version']" />
            <x-info-field label="Redis" :value="$info['redis_version']" />
            <x-info-field label="GCC" :value="$info['gcc_version']" />
        </x-info-card>

        {{-- Memory --}}
        <x-info-card title="Memory">
            <x-info-field label="Used" :value="$info['used_memory_human']" />
            <x-info-field label="Peak" :value="$info['used_memory_peak_human']" />
            <x-info-field label="Dataset" :value="$info['used_memory_dataset_perc'] . '%'" />
            <x-info-field label="Max" :value="$info['maxmemory_human']" />
            <x-info-field label="Policy" :value="ucfirst($info['maxmemory_policy'])" />
        </x-info-card>

        {{-- Clients --}}
        <x-info-card title="Clients">
            <x-info-field label="Connected" :value="$info['connected_clients']" />
            <x-info-field label="Blocked" :value="$info['blocked_clients']" />
            <x-info-field label="Rejected Connections" :value="$info['rejected_connections']" />
        </x-info-card>

        {{-- Persistence --}}
        <x-info-card title="Persistence">
            <x-info-field label="RDB Saves" :value="$info['rdb_saves']" />
            <x-info-field label="AOF Enabled" :value="$info['aof_enabled'] ? 'Yes' : 'No'" />
            <x-info-field label="Last Save Status" :value="$info['rdb_last_bgsave_status']" />
        </x-info-card>

        {{-- Performance --}}
        <x-info-card title="Performance">
            <x-info-field label="Uptime" :value="$info['uptime_in_seconds'] . ' sec'" />
            <x-info-field label="Ops/sec" :value="$info['instantaneous_ops_per_sec']" />
            <x-info-field label="Total Commands" :value="$info['total_commands_processed']" />
            <x-info-field label="CPU Sys" :value="$info['used_cpu_sys']" />
            <x-info-field label="CPU User" :value="$info['used_cpu_user']" />
        </x-info-card>
    </div>
</div>



<x-footer />