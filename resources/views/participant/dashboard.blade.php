<x-app-layout>
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 overflow-hidden shadow-sm rounded-2xl mb-6">
            <div class="p-4 sm:p-6 flex flex-col sm:flex-row items-center sm:items-start justify-between gap-4 text-center sm:text-left">
                <div>
                    <h2 class="text-xl sm:text-2xl font-bold bg-clip-text text-transparent bg-gradient-to-r from-purple-400 to-blue-400">Team: {{ $team->name }}</h2>
                    <p class="text-sm sm:text-base text-gray-400 mt-1">Current Score: <span class="font-bold text-emerald-400">{{ $currentScore }} pts</span></p>
                </div>
                <div class="sm:text-right bg-black/20 sm:bg-transparent px-6 py-2 sm:p-0 rounded-xl sm:rounded-none w-full sm:w-auto">
                    <p class="text-xs sm:text-sm text-gray-400 uppercase tracking-widest sm:normal-case sm:tracking-normal">Progress</p>
                    <h3 class="text-xl font-bold text-white">Stage {{ $progress ? $progress->currentStage->order : 0 }}</h3>
                </div>
            </div>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-500/50 text-emerald-300">
                <i class="fa-solid fa-check-circle mr-2"></i> {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 p-4 rounded-xl bg-red-500/20 border border-red-500/50 text-red-300">
                <i class="fa-solid fa-triangle-exclamation mr-2"></i> {{ session('error') }}
            </div>
        @endif

        <div class="bg-white/5 backdrop-blur-xl border border-white/10 p-8 rounded-2xl text-center">
            <div class="w-20 h-20 mx-auto bg-purple-500/20 rounded-full flex items-center justify-center mb-4 border border-purple-500/30 shadow-[0_0_15px_rgba(168,85,247,0.4)]">
                <i class="fa-solid fa-qrcode text-3xl text-purple-400"></i>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Find the Next Location!</h3>
            
            @if($isHuntCompleted)
                <div class="my-6 p-8 bg-gradient-to-br from-fuchsia-500/10 to-cyan-500/10 border border-fuchsia-500/30 rounded-3xl text-center shadow-[0_0_40px_rgba(217,70,239,0.2)] relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-cyan-500/20 rounded-full blur-[80px] pointer-events-none"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-fuchsia-500/20 rounded-full blur-[60px] pointer-events-none"></div>
                    <div class="text-5xl mb-4 animate-[bounce_3s_ease-in-out_infinite] relative z-10">🏆</div>
                    <h4 class="text-3xl font-extrabold text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400 mb-2 relative z-10">Hunt Completed!</h4>
                    <p class="text-fuchsia-100/90 relative z-10">{{ $team->event->completion_message ?: 'You have successfully found all locations and solved all puzzles! Wait for the final results on the leaderboard.' }}</p>
                </div>
            @elseif(!$nextStage)
                <div class="my-6 p-6 bg-gray-500/10 border border-gray-500/30 rounded-xl text-center text-gray-400">
                    The hunt hasn't started yet or no stages are available.
                </div>
            @else
                @if($nextStageClue)
                    <div class="my-6 p-6 bg-blue-500/10 border border-blue-500/30 rounded-xl text-left shadow-inner">
                        <p class="text-sm text-blue-300 font-bold uppercase tracking-wider mb-2"><i class="fa-solid fa-map-location-dot mr-2"></i>Location Clue</p>
                        <p class="text-xl text-white font-medium">"{{ $nextStageClue }}"</p>
                    </div>
                @else
                    <div class="my-6 p-6 bg-blue-500/10 border border-blue-500/30 rounded-xl text-center shadow-inner">
                        <p class="text-lg text-white font-medium">Look around carefully for the next QR code!</p>
                    </div>
                @endif
            
                <button id="scan-btn" class="px-6 py-3 bg-gradient-to-r from-purple-600 to-blue-600 hover:from-purple-700 hover:to-blue-700 text-white font-bold rounded-xl shadow-lg shadow-purple-500/30 transition transform hover:scale-105">
                    <i class="fa-solid fa-camera mr-2"></i> Open Scanner
                </button>
            @endif
            
            <div id="reader" class="hidden mt-6 mx-auto rounded-xl overflow-hidden shadow-lg border border-purple-500/50" style="max-width: 400px;"></div>
        </div>
    </div>

    @if(count($history) > 0)
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-6">
        <div class="bg-white/5 backdrop-blur-xl border border-white/10 overflow-hidden shadow-sm rounded-2xl">
            <div class="p-6">
                <h3 class="text-xl font-bold text-white mb-4"><i class="fa-solid fa-chart-line mr-2 text-purple-400"></i> Completed Stages Timeline</h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-300 border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 text-gray-400 bg-black/20 text-xs uppercase tracking-wider">
                                <th class="py-4 px-6 font-semibold rounded-tl-lg">Stage Name</th>
                                <th class="py-4 px-6 font-semibold text-center">Status</th>
                                <th class="py-4 px-6 font-semibold text-center">Points</th>
                                <th class="py-4 px-6 font-semibold">Start</th>
                                <th class="py-4 px-6 font-semibold">Finish</th>
                                <th class="py-4 px-6 font-semibold text-right rounded-tr-lg">Duration</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($history as $item)
                            @php
                                $isFailed = $item['earned_points'] == 0 && !$item['is_revoked'];
                                $isSuccess = !$isFailed && !$item['is_revoked'];
                                $isRed = $item['is_overdue'] || $isFailed;
                            @endphp
                            <tr class="hover:bg-white/5 transition">
                                <td class="py-4 px-6 font-medium text-white">{{ $item['stage_name'] }}</td>
                                <td class="py-4 px-6 text-center">
                                    @if($item['is_revoked'])
                                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full border border-red-500/30 uppercase tracking-widest text-[10px] line-through">Revoked</span>
                                    @elseif($isFailed)
                                        <span class="bg-red-500/20 text-red-400 px-3 py-1 rounded-full border border-red-500/30 uppercase tracking-widest text-[10px]"><i class="fa-solid fa-xmark mr-1"></i> Failed</span>
                                    @else
                                        <span class="bg-emerald-500/20 text-emerald-400 px-3 py-1 rounded-full border border-emerald-500/30 uppercase tracking-widest text-[10px]"><i class="fa-solid fa-check mr-1"></i> Success</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center font-black {{ $isSuccess ? 'text-emerald-400' : 'text-gray-500' }}">{{ $item['earned_points'] }}</td>
                                <td class="py-4 px-6 text-gray-300 text-sm">{{ $item['start_time']->format('h:i A') }}</td>
                                <td class="py-4 px-6 text-gray-300 text-sm">{{ $item['finish_time']->format('h:i A') }}</td>
                                <td class="py-4 px-6 text-right font-bold text-sm {{ $isRed ? 'text-red-500' : 'text-emerald-400' }}">{{ $item['duration_string'] }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>
<script>
    document.getElementById('scan-btn').addEventListener('click', function() {
        document.getElementById('reader').classList.remove('hidden');
        const html5QrCode = new Html5Qrcode("reader");
        
        const qrCodeSuccessCallback = (decodedText, decodedResult) => {
            html5QrCode.stop().then((ignore) => {
                window.location.href = decodedText; // Automatically redirects to the scanned URL
            }).catch((err) => {
                console.log(err);
            });
        };
        
        const config = { fps: 10, qrbox: { width: 250, height: 250 } };
        
        html5QrCode.start({ facingMode: "environment" }, config, qrCodeSuccessCallback)
        .catch((err) => {
            console.error(err);
            alert("Camera access denied or no camera found. Please check permissions.");
        });
    });
</script>
</x-app-layout>
