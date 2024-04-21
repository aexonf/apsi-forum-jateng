import React, { useEffect, useRef, useState } from "react";
import { cn } from "@/lib/utils";
import Layout from "@/components/elements/layout";
import { Button } from "@/components/ui/button";
import {
    Card,
    CardContent,
    CardFooter,
    CardHeader,
} from "@/components/ui/card";
import axios from "axios";
import { Skeleton } from "@/components/ui/skeleton";
import { LogOut, Pen } from "lucide-react";
import { toast } from "sonner";

export default function Home() {
    const token = localStorage.getItem("token");
    const [profile, setProfile] = useState(null);
    const [loading, setLoading] = useState(false);

    const fileInputRef = useRef(null);
    const [imagePreview, setImagePreview] = useState(null);

    const handleUploadClick = () => {
        fileInputRef.current.click();
    };
    const handleImageChange = async (img) => {
        try {
            setImagePreview(URL.createObjectURL(img));

            const formData = new FormData();
            formData.append("image", img);
            formData.append("test", 123);

            const response = await axios.post(
                "/api/v1/user",
                formData,
                {
                    headers: {
                        Authorization: `Bearer ${token}`,
                        "Content-Type": "multipart/form-data",
                    },
                }
            );

            console.log(response);
        } catch (error) {
            console.error(error);
            toast.error(error.response.data.message);
        }
    };

    useEffect(() => {
        async function fetchProfile() {
            setLoading(true);
            const response = await axios.get("/api/v1/user", {
                headers: {
                    Authorization: `Bearer ${token}`,
                },
            });
            setProfile(response.data.data);
            setLoading(false);
        }
        fetchProfile();
    }, []);

    return (
        <Layout>
            <div className="flex items-center justify-between gap-3 my-4">
                <h3 className="scroll-m-20 text-2xl font-semibold tracking-tight">
                    Profil
                </h3>
            </div>
            <div className="w-full">
                <Card>
                    <CardHeader />
                    <CardContent className="flex justify-center items-center flex-col">
                        <div className="relative flex">
                            {loading ? (
                                <Skeleton className="rounded-full size-40" />
                            ) : (
                                <>
                                    {imagePreview ? (
                                        <img
                                            src={imagePreview}
                                            alt="preview"
                                            id="preview"
                                            className="size-40 bg-muted rounded-full object-cover"
                                        />
                                    ) : (
                                        profile?.img_url && (
                                            <img
                                                src={`/storage/${profile?.img_url}`}
                                                alt="profile"
                                                id="preview"
                                                className="size-40 bg-muted rounded-full"
                                            />
                                        )
                                    )}
                                    <input
                                        type="file"
                                        className="hidden"
                                        ref={fileInputRef}
                                        onChange={(e) => {
                                            handleImageChange(
                                                e.target.files[0]
                                            );
                                        }}
                                    />
                                    <Button
                                        variant="ghost"
                                        size="icon"
                                        className="absolute right-0 bottom-0"
                                        onClick={handleUploadClick}
                                    >
                                        <Pen className="text-primary" />
                                    </Button>
                                </>
                            )}
                        </div>
                        <div
                            className={cn(
                                "my-4 flex items-center flex-col",
                                loading ? "gap-y-4" : "gap-y-2"
                            )}
                        >
                            {loading ? (
                                <>
                                    <Skeleton className="h-5 w-[250px]" />
                                    <Skeleton className="h-5 w-[200px]" />
                                </>
                            ) : (
                                <>
                                    <h4 className="scroll-m-20 text-xl font-semibold tracking-tight">
                                        {profile?.name}
                                    </h4>
                                    <h5 className="scroll-m-20 text-lg font-semibold tracking-tight">
                                        {profile?.label}
                                    </h5>
                                </>
                            )}
                        </div>
                    </CardContent>
                    <CardFooter className="flex-col items-start">
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl tracking-tight">
                                NIP
                            </h4>
                            {loading ? (
                                <Skeleton className="h-5 w-[250px]" />
                            ) : (
                                <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                    {profile?.id_number}
                                </h4>
                            )}
                        </div>
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl tracking-tight">
                                Email
                            </h4>
                            {loading ? (
                                <Skeleton className="h-5 w-[250px]" />
                            ) : (
                                <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                    {profile?.email}
                                </h4>
                            )}
                        </div>
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl tracking-tight">
                                No. HP
                            </h4>
                            {loading ? (
                                <Skeleton className="h-5 w-[250px]" />
                            ) : (
                                <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                    {profile?.phone_number}
                                </h4>
                            )}
                        </div>
                        <div className="my-2">
                            <h4 className="scroll-m-20 text-xl tracking-tight">
                                Tingkat
                            </h4>
                            {loading ? (
                                <Skeleton className="h-5 w-[250px]" />
                            ) : (
                                <h4 className="scroll-m-20 text-xl font-bold tracking-tight">
                                    {profile?.level}
                                </h4>
                            )}
                        </div>
                    </CardFooter>
                </Card>
            </div>
            <Button
                className="w-full my-4 gap-4"
                variant="destructive"
                size="lg"
            >
                <LogOut className="size-5" strokeWidth={3} />
                <span className="font-semibold">Keluar</span>
            </Button>
        </Layout>
    );
}
