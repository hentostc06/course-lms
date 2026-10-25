<x-filament-panels::page>
@if($notice)<div role="status" style="padding:12px;border-left:3px solid #D94A2F;background:#FFF0E9;color:#612B1E">{{ $notice }}</div>@endif
@if($errors->any())<div role="alert" style="color:#B83725">{{ $errors->first() }}</div>@endif
<x-filament::section heading="Pengajuan instruktur" description="Persetujuan instruktur tercatat dalam audit event.">
 <div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse;text-align:left"><thead><tr><th style="padding:12px">Nama</th><th>Email</th><th>Headline</th><th>Tindakan</th></tr></thead><tbody>
 @forelse($this->getInstructors() as $profile)<tr style="border-top:1px solid #ccc"><td style="padding:12px">{{ $profile->user?->name }}</td><td>{{ $profile->user?->email }}</td><td>{{ $profile->headline ?: '—' }}</td><td><x-filament::button size="sm" wire:click="approveInstructor({{ $profile->id }})" wire:confirm="Setujui instruktur ini?">Setujui</x-filament::button></td></tr>
 @empty<tr><td colspan="4" style="padding:16px">Belum ada pengajuan instruktur.</td></tr>@endforelse
 </tbody></table></div>
</x-filament::section>
<x-filament::section heading="Review kursus" description="Periksa silabus sebelum publikasi; penolakan wajib disertai alasan.">
 <div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse;text-align:left"><thead><tr><th style="padding:12px">Kursus</th><th>Instruktur</th><th>Kategori</th><th>Tindakan</th></tr></thead><tbody>
 @forelse($this->getCourses() as $course)<tr style="border-top:1px solid #ccc"><td style="padding:12px"><strong>{{ $course->title }}</strong><div style="font-size:12px;max-width:280px">{{ $course->summary }}</div></td><td>{{ $course->instructor?->user?->name }}</td><td>{{ $course->category?->name }}</td><td style="white-space:nowrap"><x-filament::button size="sm" wire:click="approveCourse({{ $course->id }})" wire:confirm="Publikasikan kursus ini?">Terbitkan</x-filament::button> <x-filament::button size="sm" color="danger" wire:click="selectRejectedCourse({{ $course->id }})">Tolak</x-filament::button></td></tr>
 @empty<tr><td colspan="4" style="padding:16px">Tidak ada kursus menunggu review.</td></tr>@endforelse
 </tbody></table></div>
 @if($selectedCourse)<form wire:submit="rejectCourse" style="margin-top:24px;display:grid;gap:12px;max-width:650px"><label for="reason">Alasan penolakan kursus #{{ $selectedCourse }}</label><textarea id="reason" wire:model="reviewReason" minlength="10" maxlength="2000" rows="3" required style="width:100%;padding:10px;border:1px solid #aaa;border-radius:5px;color:#171716;background:#fff"></textarea><div><x-filament::button type="submit" color="danger">Simpan penolakan</x-filament::button></div></form>@endif
</x-filament::section>
<x-filament::section heading="Kategori kursus" description="Kategori aktif muncul pada form instruktur dan katalog.">
 <form wire:submit="createCategory" style="display:flex;flex-wrap:wrap;align-items:flex-end;gap:12px;margin-bottom:24px">
  <label style="display:grid;gap:6px;flex:1;min-width:180px">Nama kategori <input wire:model="categoryName" required maxlength="120" placeholder="Contoh: Audit dan kepatuhan" style="padding:10px;border:1px solid #aaa;border-radius:5px;color:#171716;background:#fff"></label>
  <label style="display:grid;gap:6px;flex:2;min-width:220px">Deskripsi <input wire:model="categoryDescription" maxlength="1000" placeholder="Deskripsi singkat" style="padding:10px;border:1px solid #aaa;border-radius:5px;color:#171716;background:#fff"></label>
  <x-filament::button type="submit">Tambah kategori</x-filament::button>
 </form>
 <div style="overflow-x:auto"><table style="width:100%;border-collapse:collapse;text-align:left"><thead><tr><th style="padding:12px">Kategori</th><th>Status</th><th>Aksi</th></tr></thead><tbody>
 @forelse($this->getCategories() as $category)<tr style="border-top:1px solid #ccc"><td style="padding:12px">{{ $category->name }}</td><td>{{ $category->is_active ? 'Aktif' : 'Nonaktif' }}</td><td><x-filament::button size="sm" color="gray" wire:click="toggleCategory({{ $category->id }})">{{ $category->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</x-filament::button></td></tr>
 @empty<tr><td colspan="3" style="padding:16px">Belum ada kategori.</td></tr>@endforelse
 </tbody></table></div>
</x-filament::section>
</x-filament-panels::page>
